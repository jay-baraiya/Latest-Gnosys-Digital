$(document).ready(function () {
    if($('#manage-users-list').length > 0) {
		$('#manage-users-list').DataTable({
			"bFilter": false, 
			"bInfo": false,
			"ordering": true,
			"autoWidth": true,
			"language": {
				search: ' ',
				sLengthMenu: '_MENU_',
				searchPlaceholder: "Search",
				info: "_START_ - _END_ of _TOTAL_ items",
				"lengthMenu":     "Show _MENU_ entries",
				paginate: {
					next: '<i class="ti ti-chevron-right"></i> ',
					previous: '<i class="ti ti-chevron-left"></i> '
				},
			},
			initComplete: (settings, json)=>{
				$('.dataTables_paginate').appendTo('.datatable-paginate');
				$('.dataTables_length').appendTo('.datatable-length');
			},  
			"columns": [
				{ "render": function ( data, type, row ){
					return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
				}},
				{ "render": function ( data, type, row ){
					return '<div class="set-star rating-select"><i class="ti ti-star-filled fs-16"></i></div>';
				}},
				{ "render": function ( data, type, row ){
					return '<h6 class="d-flex align-items-center fs-14 fw-medium mb-0"><a href="javascript:void(0);" class="avatar avatar-rounded me-2"><img src="'+row['customer_image']+'" alt="User Image"></a><a href="javascript:void(0);" class="d-flex flex-column">'+row['customer_name']+' <span class="text-body fs-13 mt-1 d-inline-block fw-normal">'+row['customer_no']+' </span></a></h6>';
				}},
				{ "data": "phone" },
				{ "data": "email" },
				{ "data": "created" },
				{ "data": "last_activity" },
				{ "render": function ( data, type, row ){
					if(row['status'] == "0") { var class_name = "bg-success";var status_name ="Active" } else { var class_name = "bg-danger";var status_name ="Inactive"}
					return '<span class="badge badge-pill badge-status '+class_name+'" >'+status_name+'</span>';
				}},
				{ "render": function ( data, type, row ){
					return '<div class="dropdown table-action"><a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edit"><i class="ti ti-edit text-blue"></i> Edit</a><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_contact"><i class="ti ti-trash"></i> Delete</a></div></div>';
				}}
			]
		});
	}
});