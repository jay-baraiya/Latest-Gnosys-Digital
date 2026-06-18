<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\CustomField;
use App\Models\CustomFieldType;
use App\Models\ServiceFeature;
use App\Models\ServiceVariant;
use App\Models\State;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Pest\ArchPresets\Custom;
use Throwable;

class CommonController extends Controller
{
    public function getCountries(Request $request)
    {
        $query = $request->input('q');
        $countries = Country::where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($country) {
                return [
                    'id' => $country->id,
                    'text' => $country->name
                ];
            });
        return response()->json($countries);
    }

    public function getStates(Request $request)
    {
        $query = $request->input('q');
        $states = State::where('country_id', $request->country_id)
            ->where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($state) {
                return [
                    'id' => $state->id,
                    'text' => $state->name
                ];
            });
        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $query = $request->input('q');
        $cities = City::where('state_id', $request->state_id)
            ->where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($city) {
                return [
                    'id' => $city->id,
                    'text' => $city->name
                ];
            });
        return response()->json($cities);
    }

    public function getFieldTypeData(Request $request) {
        $request->validate([
            'type_id' => 'required|exists:custom_field_types,id'
        ]);

        $fieldType = CustomFieldType::findOrFail($request->type_id);
        $customfields = CustomField::find($request->field_id);

        $fieldTypeParams = json_decode($fieldType->params, true) ?? [];
        $options = !empty($customfields->options) ? json_decode($customfields->options, true) : [];
        $params = !empty($customfields->params) ? json_decode($customfields->params, true) : [];

        $html = view('admin.custom-field.type_setting', [
            'fieldType' => $fieldType,
            'field_type_params' => $fieldTypeParams,
            'options' => $options,
            'params' => $params,
            'index' => $request->index,
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public static function storeCustomFields(Request $request)
    {
        $customFieldData = $request->input('custom_field');

        if (!is_array($customFieldData) || !isset($customFieldData['fields']) || !is_array($customFieldData['fields'])) {
            return [];
        }

        $request->validate([
            'custom_field.fields.*.name' => 'nullable|string|max:255|distinct',
            'custom_field.fields.*.custom_field_type_id' => 'nullable|exists:custom_field_types,id',
        ],[
            'custom_field.fields.*.name.required' => 'The custom field name is required.',
            'custom_field.fields.*.name.max' => 'The custom field name may not be greater than 255 characters.',
            'custom_field.fields.*.name.distinct' => 'Duplicate custom field names are not allowed.',

            'custom_field.fields.*.custom_field_type_id.required' => 'Please select a field type.',
            'custom_field.fields.*.custom_field_type_id.exists' => 'The selected field type is invalid.',
        ]);

        $module_type  = !empty($customFieldData['module_type']) ? $customFieldData['module_type'] : 'service';
        $field_id  = !empty($customFieldData['field_id']) ? $customFieldData['field_id'] : [];
        $recode_id  = !empty($customFieldData['recode_id']) ? $customFieldData['recode_id'] : null;
        $fields  = $customFieldData['fields'];
        $params  = $customFieldData['params'] ?? [];
        $options = $customFieldData['options'] ?? [];

        $all_field_ids = !empty($customFieldData['all_field_ids']) ? json_decode($customFieldData['all_field_ids'], true) : [];

        $diff_id = array_values(
            array_diff(
                $all_field_ids,
                $field_id
            )
        );

        // $checkSlug = CustomField::query()->select(['id','slug'])
        //         ->when(!empty($all_field_ids), function($q) use ($all_field_ids) {
        //             $q->whereNotIn('id', $all_field_ids);
        //         })
        //         ->pluck('slug')->toArray();

        $insertedIds = [];

        // DB::beginTransaction();

        try {

            if (!empty($diff_id)) {
                CustomField::whereIn('id', $diff_id)->delete();
            }

            foreach ($fields as $index => $field) {
                if (empty($field['name']) || empty($field['custom_field_type_id'])) {
                    continue;
                }

                $slug = Str::slug($field['name']);

                // if (in_array($slug, $checkSlug)) {
                //     throw new Exception("A custom field with the name '{$field['name']}' already exists.");
                // }

                $fieldParams  = isset($params[$index]) ? $params[$index] : [];
                $fieldOptions = isset($options[$index]) ? $options[$index] : [];

                $customField = CustomField::updateOrCreate([
                    'id' => isset($field_id[$index]) ? $field_id[$index] : null,
                    'recode_id' => $recode_id
                ],[
                    'module_type'          => $module_type,
                    'name'                 => $field['name'],
                    'slug'                 => $slug,
                    'custom_field_type_id' => $field['custom_field_type_id'],
                    'status'               => $request->input('status', 1),
                    'options'              => !empty($fieldOptions) ? json_encode($fieldOptions) : '',
                    'params'               => !empty($fieldParams) ? json_encode($fieldParams) : '',
                    'recode_id' => $recode_id
                ]);

                $insertedIds[] = $customField->id;
            }

            // DB::commit();

            return $insertedIds;

        } catch (Exception $e) {
            // DB::rollBack();

            Log::error('storeCustomFields Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            throw $e;
        }
    }

    public function getCategories(Request $request)
    {
        $query = Category::query()->where('status', 1);

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        $categories = $query->select('id', 'name')->limit(15)->get();

        return response()->json($categories);
    }

    public static function storeServiceVariants(Request $request)
    {
        $variantData = $request->input('variant');

        if (!is_array($variantData) || !isset($variantData)) {
            return [];
        }

        $service_id = !empty($variantData['service_id']) ? $variantData['service_id'] : null;

        $insertedIds = [];

        // DB::beginTransaction();

        try {
            unset($variantData['service_id']);
            foreach ($variantData as $key => $variant) {

                if (empty($variant['name']) && empty($variant['price']) && empty($variant['description']) && !empty($variant['variant_id'])) {
                    ServiceVariant::query()->where('id', $variant['variant_id'])->where('service_id', $service_id)->delete();
                }

                if (empty($variant['name']) && empty($variant['price']) && empty($variant['description'])) {
                    continue;
                }

                $features = ((is_array($variant['features']) && !empty($variant['features'])) ? json_encode($variant['features']) : null );

                $varinatid = ServiceVariant::updateOrCreate([
                    'id' => !empty($variant['variant_id']) ? $variant['variant_id'] : null,
                    'service_id' => $service_id
                ],[
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'description' => $variant['description'],
                    'service_id' => $service_id,
                    'features' => $features
                ]);

                if($varinatid->id) {
                    $insertedIds[] = $varinatid->id;
                }
            }

            // DB::commit();

            if (empty($insertedIds)) {
                // DB::rollBack();
            }

            return $insertedIds;

        } catch (Throwable $e) {
            // DB::rollBack();

            throw $e;
        }
    }

    public static function storeServiceFeatures(Request $request)
    {
        $featureData = $request->input('features');
        if (!is_array($featureData) || !isset($featureData)) {
            return [];
        }

        $service_id = !empty($featureData['service_id']) ? $featureData['service_id'] : null;

        $insertedIds = [];

        // DB::beginTransaction();

        try {
            unset($featureData['service_id']);
            foreach ($featureData as $key => $feature) {

                if (empty($feature['name']) && !empty($feature['feature_id']) && !empty($service_id)) {
                    ServiceFeature::query()->where('id', $feature['feature_id'])->where('service_id', $service_id)->delete();
                }

                if (empty($feature['name'])) {
                    continue;
                }

                $feature = ServiceFeature::updateOrCreate([
                    'id' => isset($feature['feature_id']) ? $feature['feature_id'] : null,
                    'service_id' => $service_id
                ],[
                    'name' => $feature['name'],
                    'service_id' => $service_id
                ]);

                if($feature->id) {
                    $insertedIds[] = $feature->id;
                }
            }

            // DB::commit();

            if (empty($insertedIds)) {
                // DB::rollBack();
            }

            return $insertedIds;

        } catch (Throwable $e) {
            // DB::rollBack();

            throw $e;
        }
    }

    public static function updateUserData() {

        if (auth()->check()) {
            try {
                $user = auth()->user();

                // $order = Order::whereNull('user_id')
                //         ->where('guest_email', $user->email)
                //         ->update(['user_id' => $user->id]);

                // $address = Address::whereNull('user_id')
                //         ->where('user_email', $user->email)
                //         ->update(['user_id' => $user->id]);
            } catch (\Throwable $e) {
                Log::error('updateUserData() error -> '. $e->getMessage());
            }
        }
    }
}
