<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inquiry_date' => 'required|date',
            'source' => 'nullable|in:website,call,whatsapp,email,walkin',
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'course_id' => 'nullable|exists:courses,id',
            'description' => 'nullable|string',
            'status' => 'required|in:new,in_progress,converted,rejected',
            'priority' => 'required|in:low,medium,high',

            'followup_date' => 'required|array',
            'followup_date.*' => 'required|date_format:Y-m-d',

            'followup_time' => 'required|array',
            'followup_time.*' => 'required|date_format:H:i:s',

            'next_followup_datetime' => 'nullable|array',
            'next_followup_datetime.*' => 'nullable|date_format:Y-m-d\TH:i',

            'followup_type' => 'required|array',
            'followup_type.*' => 'required|integer',

            'followup_status' => 'required|array',
            'followup_status.*' => 'required|integer',

            'followup_remark' => 'required|array',
            'followup_remark.*' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'inquiry_date.required' => 'Inquiry date is required.',
            'inquiry_date.date' => 'Inquiry date must be a valid date.',
            'source.in' => 'Invalid source.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'country_id.required' => 'Country is required.',
            'country_id.exists' => 'Selected country does not exist.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'Selected state does not exist.',
            'city_id.required' => 'City is required.',
            'city_id.exists' => 'Selected city does not exist.',
            'cource_id.exists' => 'Selected course does not exist.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status.',
            'priority.required' => 'Priority is required.',
            'priority.in' => 'Invalid priority.',

            'followup_date.required' => 'Follow-up date is required.',
            'followup_date.array' => 'Follow-up date must be an array.',
            'followup_date.*.required' => 'Each follow-up date is required.',
            'followup_date.*.date_format' => 'Each follow-up date must be a valid date.',

            'followup_time.required' => 'Follow-up time is required.',
            'followup_time.array' => 'Follow-up time must be an array.',
            'followup_time.*.required' => 'Each follow-up time is required.',
            'followup_time.*.date_format' => 'Each follow-up time must be a valid time.',

            'next_followup_datetime.required' => 'Next follow-up date and time is required.',
            'next_followup_datetime.array' => 'Next follow-up date and time must be an array.',
            'next_followup_datetime.*.required' => 'Each next follow-up date and time is required.',
            'next_followup_datetime.*.date_format' => 'Each next follow-up date and time must be a valid date and time.',

            'followup_type.required' => 'Follow-up type is required.',
            'followup_type.array' => 'Follow-up type must be an array.',
            'followup_type.*.required' => 'Each follow-up type is required.',
            'followup_type.*.in' => 'Invalid follow-up type.',

            'followup_status.required' => 'Follow-up status is required.',
            'followup_status.array' => 'Follow-up status must be an array.',
            'followup_status.*.required' => 'Each follow-up status is required.',
            'followup_status.*.in' => 'Invalid follow-up status.',

            'followup_remark.required' => 'Follow-up remark is required.',
            'followup_remark.array' => 'Follow-up remark must be an array.',
            'followup_remark.*.required' => 'Each follow-up remark is required.',
        ];
    }
}
