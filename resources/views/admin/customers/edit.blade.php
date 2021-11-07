@extends('admin.layouts.master')
@section('title',$title)
@section('content')
  <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader" kt-hidden-height="54" style="">
      <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
          <!--begin::Page Heading-->
          <div class="d-flex align-items-baseline flex-wrap mr-5">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard</h5>
            <!--end::Page Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Manage Customer</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Edit Customer
              </li>
              <li class="breadcrumb-item text-muted">
               {{ $user->name }}
              </li>
            </ul>
            <!--end::Breadcrumb-->
          </div>
          <!--end::Page Heading-->
        </div>
        <!--end::Info-->
      </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
      <!--begin::Container-->
      <div class="container">
        <!--begin::Card-->
        <div class="card card-custom card-sticky" id="kt_page_sticky_card">
          <div class="card-header" style="">
            <div class="card-title">
              <h3 class="card-label">Customer Edit Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('customers.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

{{--              <div class="btn-group">--}}
{{--                <a href=""  onclick="event.preventDefault(); document.getElementById('client_update_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">--}}
{{--                  <i class="ki ki-check icon-sm"></i>update</a>--}}
{{--              </div>--}}
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
              {{ Form::model($user, [ 'method' => 'PATCH','route' => ['customers.update', $user->id],'class'=>'form' ,"id"=>"client_update_form", 'enctype'=>'multipart/form-data'])}}
              @csrf
                  <div class="card-body">
                      <h3 class="text-dark font-weight-bold mb-10">Customer Info: </h3>
                      <div class="form-group row">
                          <div class="col-lg-4">
                              <label>Name:</label>
                              {{ Form::text('name', null, ['class' => 'form-control','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}

                          </div>
                          <div class="col-lg-4">
                              <label>Pricing Plan</label>
                              <div>
                                  <select class="form-control select2" id="kt_select2_1" name="plan">
                                      <option value="{{$user['plan']}}">{{$user['plan']}}</option>
                                      @foreach($plans as $plan)
                                          <option value="{{$plan['title']}}">{{$plan['title']}}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <span class="form-text text-muted">If plan does not exist, <a href="#">Create a Plan</a></span>
                          </div>

                          <div class="col-lg-4">
                              <label>Active</label>
                              <div class="col-4">
                                 <span class="switch switch-outline switch-icon switch-success">
                                  <label><input type="checkbox" {{ ($user->active) ?'checked':'' }} name="active" value="1">
                                    <span></span>
                                  </label>
                                </span>
                              </div>
                          </div>

                      </div>
                  </div>
              {{Form::close()}}

              <hr>
              <div class="tab">
                  <button class="tablinks" onclick="openCity(event, 'contact')">Contact Details</button>
                  <button class="tablinks" onclick="openCity(event, 'address')">Address</button>
                  <button class="tablinks" onclick="openCity(event, 'account_detail')">Account Detail</button>
                  <button class="tablinks" onclick="openCity(event, 'notes')">Notes</button>
                  <button class="tablinks" onclick="openCity(event, 'attachments')">Attachments</button>
                  <button class="tablinks" onclick="openCity(event, 'sites')">Sites</button>
              </div>

              <div id="contact" class="tabcontent">
                  <form class="form" id="contact_form" method="post" action="{{$user['primary_contact']->contact_name) ? route('store_customer_contacts') : route('update_customer_contacts')}}">
                      @csrf
                      <input type="hidden" value="{{$user->id}}" name="c_id">
                      <input type="hidden" value="" name="c_name">
                      <input type="hidden" value="" name="c_plan">
                      <input type="hidden" value="" name="c_active">
                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Primary Contact: </h3>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Contact Name(required):</label>
                                  @if(isset($user['primary_contact']->contact_name))
                                      <input type="text" name="p_contact_name" class="form-control" placeholder="Enter Contact name" value="{{$user['primary_contact']->contact_name}}"/>
                                    @else
                                      <input type="text" name="p_contact_name" class="form-control" placeholder="Enter Contact name"/>
                                  @endif

                                  <span class="form-text text-muted" id="p_span">Please enter your contact name</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Position:</label>
                                  @if(isset($user['primary_contact']->position))
                                      <input type="text" name="p_position" class="form-control" placeholder="Enter Position" value="{{$user['primary_contact']->position}}"/>
                                  @else
                                      <input type="text" name="p_position" class="form-control" placeholder="Enter Position"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your position</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Mobile:</label>
                                  <div class="input-group">
                                      @if(isset($user['primary_contact']->mobile))
                                          <input type="text" name="p_mobile" class="form-control" placeholder="Enter your mobile" value="{{$user['primary_contact']->mobile}}"/>
                                        @else
                                          <input type="text" name="p_mobile" class="form-control" placeholder="Enter your mobile"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-mobile"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your Phone number</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Office Phone:</label>
                                  <div class="input-group">
                                      @if(isset($user['primary_contact']->office_phone))
                                          <input type="text" name="p_office_phone" class="form-control" placeholder="Enter your office phone number" value="{{$user['primary_contact']->office_phone}}"/>
                                        @else
                                          <input type="text" name="p_office_phone" class="form-control" placeholder="Enter your office phone number"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-phone-square"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your office phone</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Fax:</label>
                                  <div class="input-group">
                                      @if(isset($user['primary_contact']->fax))
                                          <input type="text" name="p_fax" class="form-control" placeholder="Enter your Fax" value="{{$user['primary_contact']->fax}}"/>
                                      @else
                                          <input type="text" name="p_fax" class="form-control" placeholder="Enter your Fax"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-fax"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your fax</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>email:</label>
                                  <div class="input-group">
                                      @if(isset($user['primary_contact']->email))
                                          <input type="email" name="p_email" class="form-control" placeholder="Enter your office Email" value="{{$user['primary_contact']->email}}"/>
                                      @else
                                          <input type="email" name="p_email" class="form-control" placeholder="Enter your office Email"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-envelope-o"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your email</span>
                              </div>
                          </div>
                      </div>
                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Secondary Contact: </h3>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Contact Name:</label>
                                  @if(isset($user['secondary_contact']->contact_name))
                                      <input type="text" name="s_contact_name" class="form-control" placeholder="Enter Contact name" value="{{$user['secondary_contact']->contact_name}}"/>
                                  @else
                                      <input type="text" name="s_contact_name" class="form-control" placeholder="Enter Contact name"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your contact name</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Position:</label>
                                  @if(isset($user['secondary_contact']->position))
                                      <input type="text" name="s_position" class="form-control" placeholder="Enter Position" value="{{$user['secondary_contact']->position}}"/>
                                  @else
                                      <input type="text" name="s_position" class="form-control" placeholder="Enter Position"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your position</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Mobile:</label>
                                  <div class="input-group">
                                      @if(isset($user['secondary_contact']->mobile))
                                          <input type="text" name="s_mobile" class="form-control" placeholder="Enter your mobile" value="{{$user['secondary_contact']->mobile}}"/>
                                      @else
                                          <input type="text" name="s_mobile" class="form-control" placeholder="Enter your mobile"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-mobile"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your Phone number</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Office Phone:</label>
                                  <div class="input-group">
                                      @if(isset($user['secondary_contact']->office_phone))
                                          <input type="text" name="s_office_phone" class="form-control" placeholder="Enter your office phone number" value="{{$user['secondary_contact']->office_phone}}"/>
                                      @else
                                          <input type="text" name="s_office_phone" class="form-control" placeholder="Enter your office phone number"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-phone-square"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your office phone</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Fax:</label>
                                  <div class="input-group">
                                      @if(isset($user['secondary_contact']->fax))
                                          <input type="text" name="s_fax" class="form-control" placeholder="Enter your Fax" value="{{$user['secondary_contact']->fax}}"/>
                                      @else
                                          <input type="text" name="s_fax" class="form-control" placeholder="Enter your Fax"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-fax"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your fax</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>email:</label>
                                  <div class="input-group">
                                      @if(isset($user['secondary_contact']->email))
                                          <input type="email" name="s_email" class="form-control" placeholder="Enter your office Email" value="{{$user['secondary_contact']->email}}"/>
                                      @else
                                          <input type="email" name="s_email" class="form-control" placeholder="Enter your office Email"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-envelope-o"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your email</span>
                              </div>
                          </div>
                      </div>
                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Other Contact: </h3>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Contact Name:</label>
                                  @if(isset($user['other_contact']->contact_name))
                                      <input type="text" name="o_contact_name" class="form-control" placeholder="Enter Contact name" value="{{$user['other_contact']->contact_name}}"/>
                                  @else
                                      <input type="text" name="o_contact_name" class="form-control" placeholder="Enter Contact name"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your contact name</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Position:</label>
                                  @if(isset($user['other_contact']->position))
                                      <input type="text" name="o_position" class="form-control" placeholder="Enter Position" value="{{$user['other_contact']->position}}"/>
                                  @else
                                      <input type="text" name="o_position" class="form-control" placeholder="Enter Position"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your position</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Mobile:</label>
                                  <div class="input-group">
                                      @if(isset($user['other_contact']->mobile))
                                          <input type="text" name="o_mobile" class="form-control" placeholder="Enter your mobile"  value="{{$user['other_contact']->mobile}}"/>

                                      @else
                                          <input type="text" name="o_mobile" class="form-control" placeholder="Enter your mobile"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-mobile"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your Phone number</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Office Phone:</label>
                                  <div class="input-group">
                                      @if(isset($user['other_contact']->office_number))
                                          <input type="text" name="o_office_phone" class="form-control" placeholder="Enter your office phone number"  value="{{$user['other_contact']->office_number}}"/>
                                      @else
                                          <input type="text" name="o_office_phone" class="form-control" placeholder="Enter your office phone number">
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-phone-square"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your office phone</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Fax:</label>
                                  <div class="input-group">
                                      @if(isset($user['other_contact']->fax))
                                          <input type="text" name="o_fax" class="form-control" placeholder="Enter your Fax"  value="{{$user['other_contact']->fax}}"/>
                                      @else
                                          <input type="text" name="o_fax" class="form-control" placeholder="Enter your Fax"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-fax"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your fax</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>email:</label>
                                  <div class="input-group">
                                      @if(isset($user['other_contact']->email))
                                          <input type="email" name="o_email" class="form-control" placeholder="Enter your office Email"  value="{{$user['other_contact']->email}}"/>
                                      @else
                                          <input type="email" name="o_email" class="form-control" placeholder="Enter your office Email"/>
                                      @endif

                                      <div class="input-group-append"><span class="input-group-text"><i class="la la-envelope-o"></i></span></div>
                                  </div>
                                  <span class="form-text text-muted">Please enter your email</span>
                              </div>
                          </div>
                      </div>
                      <div class="card-footer">
                          <div class="row">
                              @if(isset($user['primary_contact']->contact_name))
                                  <div class="col-lg-6">
                                      <button onclick="event.preventDefault(); contact_form_action_update();" class="btn btn-primary mr-2">update</button>
                                  </div>
                                  @else
                                  <div class="col-lg-6">
                                      <button onclick="event.preventDefault(); contact_form_action();" class="btn btn-primary mr-2">Save</button>
                                  </div>
                                  @endif

                          </div>
                      </div>
                  </form>
              </div>

              <div id="address" class="tabcontent">
                  <form class="form"  id="address_form" method="post" action="{{route('store_customer_address')}}">

                      @csrf
                      <input type="hidden" value="{{$user->id}}" name="c_id">
                      <input type="hidden" value="" name="c_name">
                      <input type="hidden" value="" name="c_plan">
                      <input type="hidden" value="" name="c_active">
                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Primary Address: </h3>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Address Line 1(required):</label>
                                  @if(isset($user['address']['0']->p_address_line_1))
                                      <input type="text" name="p_address_line_1" class="form-control" placeholder="Enter a location" value="{{$user['address']['0']->p_address_line_1}}"/>
                                      @else
                                      <input type="text" name="p_address_line_1" class="form-control" placeholder="Enter a location"/>
                                      @endif
                                  <span class="form-text text-muted">Please enter your address line 1</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Address Line 2:</label>
                                  @if(isset($user['address']['0']->p_address_line_2))
                                      <input type="text" name="p_address_line_2" class="form-control" placeholder="Enter a location" value="{{$user['address']['0']->p_address_line_2}}"/>
                                      @else
                                      <input type="text" name="p_address_line_2" class="form-control" placeholder="Enter a location"/>
                                      @endif
                                  <span class="form-text text-muted">Please enter your address line 2</span>
                              </div>

                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Suburb:</label>
                                  @if(isset($user['address']['0']->p_suburb))
                                      <input type="text" name="p_suburb" class="form-control" placeholder="Enter your suburb"  value="{{$user['address']['0']->p_suburb}}"/>
                                  @else
                                      <input type="text" name="p_suburb" class="form-control" placeholder="Enter your suburb"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Suburb</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Postal Code:</label>
                                  @if(isset($user['address']['0']->p_postal_code))
                                      <input type="text" name="p_postal_code" class="form-control" placeholder="Enter your postal code" value="{{$user['address']['0']->p_postal_code}}"/>
                                  @else
                                      <input type="text" name="p_postal_code" class="form-control" placeholder="Enter your postal code"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Postal</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>State:</label>
                                  @if(isset($user['address']['0']->p_state))
                                      <input type="text" name="p_state" class="form-control" placeholder="Enter your state" value="{{$user['address']['0']->p_state}}"/>
                                  @else
                                      <input type="text" name="p_state" class="form-control" placeholder="Enter your state"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your State</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Opening Time:</label>
                                  @if(isset($user['address']['0']->p_opening_time))
                                      <input type="time" name="p_opening_time" class="form-control" placeholder="Enter your opening time"  value="{{$user['address']['0']->p_opening_time}}"/>
                                  @else
                                      <input type="time" name="p_opening_time" class="form-control" placeholder="Enter your opening time"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your opening time</span>
                              </div>
                          </div>
                      </div>

                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Business Address: </h3>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Address Line 1:</label>
                                  @if(isset($user['address']['0']->b_address_line_1))
                                      <input type="text" name="b_address_line_1" class="form-control" placeholder="Enter a location" value="{{$user['address']['0']->b_address_line_1}}"/>
                                  @else
                                      <input type="text" name="b_address_line_1" class="form-control" placeholder="Enter a location"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your address line 1</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Address Line 2:</label>
                                  @if(isset($user['address']['0']->b_address_line_2))
                                      <input type="text" name="b_address_line_2" class="form-control" placeholder="Enter a location" value="{{$user['address']['0']->b_address_line_2}}"/>
                                  @else
                                      <input type="text" name="b_address_line_2" class="form-control" placeholder="Enter a location"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your address line 2</span>
                              </div>

                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Suburb:</label>
                                  @if(isset($user['address']['0']->b_suburb))
                                      <input type="text" name="b_suburb" class="form-control" placeholder="Enter your suburb" value="{{$user['address']['0']->b_suburb}}"/>
                                  @else
                                      <input type="text" name="b_suburb" class="form-control" placeholder="Enter your suburb"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Suburb</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Postal Code:</label>
                                  @if(isset($user['address']['0']->b_postal_code))
                                      <input type="text" name="b_postal_code" class="form-control" placeholder="Enter your postal code" value="{{$user['address']['0']->b_postal_code}}"/>
                                  @else
                                      <input type="text" name="b_postal_code" class="form-control" placeholder="Enter your postal code"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Postal</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>State:</label>
                                  @if(isset($user['address']['0']->b_state))
                                      <input type="text" name="b_state" class="form-control" placeholder="Enter your state" value="{{$user['address']['0']->b_state}}"/>
                                  @else
                                      <input type="text" name="b_state" class="form-control" placeholder="Enter your state"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your State</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Opening Time:</label>
                                  @if(isset($user['address']['0']->b_opening_time))
                                      <input type="time" name="b_opening_time" class="form-control" placeholder="Enter your opening time" value="{{$user['address']['0']->b_opening_time}}"/>
                                  @else
                                      <input type="time" name="b_opening_time" class="form-control" placeholder="Enter your opening time"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your opening time</span>
                              </div>
                          </div>
                      </div>

                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Residential Address: </h3>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Address Line 1:</label>
                                  @if(isset($user['address']['0']->r_address_line_1))
                                      <input type="text" name="r_address_line_1" class="form-control" placeholder="Enter a location" value="{{$user['address']['0']->r_address_line_1}}"/>
                                  @else
                                      <input type="text" name="r_address_line_1" class="form-control" placeholder="Enter a location"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your address line 1</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Address Line 2:</label>
                                  @if(isset($user['address']['0']->r_address_line_2))
                                      <input type="text" name="r_address_line_2" class="form-control" placeholder="Enter a location" value="{{$user['address']['0']->r_address_line_2}}"/>
                                  @else
                                      <input type="text" name="r_address_line_2" class="form-control" placeholder="Enter a location"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your address line 2</span>
                              </div>

                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Suburb:</label>
                                  @if(isset($user['address']['0']->r_suburb))
                                      <input type="text" name="r_suburb" class="form-control" placeholder="Enter your suburb" value="{{$user['address']['0']->r_suburb}}"/>
                                  @else
                                      <input type="text" name="r_suburb" class="form-control" placeholder="Enter your suburb"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Suburb</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Postal Code:</label>
                                  @if(isset($user['address']['0']->r_postal_code))
                                      <input type="text" name="r_postal_code" class="form-control" placeholder="Enter your postal code" value="{{$user['address']['0']->r_postal_code}}"/>
                                  @else
                                      <input type="text" name="r_postal_code" class="form-control" placeholder="Enter your postal code"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Postal</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>State:</label>
                                  @if(isset($user['address']['0']->r_state))
                                      <input type="text" name="r_state" class="form-control" placeholder="Enter your state" value="{{$user['address']['0']->r_state}}"/>
                                  @else
                                      <input type="text" name="r_state" class="form-control" placeholder="Enter your state"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your State</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Opening Time:</label>
                                  @if(isset($user['address']['0']->r_opening_time))
                                      <input type="time" name="r_opening_time" class="form-control" placeholder="Enter your opening time" value="{{$user['address']['0']->r_opening_time}}"/>
                                  @else
                                      <input type="time" name="r_opening_time" class="form-control" placeholder="Enter your opening time"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your opening time</span>
                              </div>
                          </div>
                      </div>

                      <div class="card-footer">
                          <div class="row">
                              @if(isset($user['address']['0']->p_address_line_1))
                                  <div class="col-lg-6">
                                      <button onclick="event.preventDefault(); address_form_action_update();" class="btn btn-primary mr-2">update</button>
                                  </div>
                                  @else
                                  <div class="col-lg-6">
                                      <button onclick="event.preventDefault(); address_form_action();" class="btn btn-primary mr-2">Save</button>
                                  </div>
                                  @endif

                          </div>
                      </div>
                  </form>
              </div>

              <div id="account_detail" class="tabcontent">
                  <form class="form" id="account_detail_form" method="post" action="{{route('store_account_detail')}}">

                      @csrf
                      <input type="hidden" value="{{$user->id}}" name="c_id">
                      <input type="hidden" value="" name="c_name">
                      <input type="hidden" value="" name="c_plan">
                      <input type="hidden" value="" name="c_active">

                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Account Details: </h3>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Business name(required):</label>
                                  @if(isset($user['account_detail']['0']->business_name))
                                      <input type="text" name="business_name" class="form-control" placeholder="Enter a Business name" value="{{$user['account_detail']['0']->business_name}}"/>
                                  @else
                                      <input type="text" name="business_name" class="form-control" placeholder="Enter a Business name"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your business name</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>Trading name:</label>
                                  @if(isset($user['account_detail']['0']->trading_name))
                                      <input type="text" name="trading_name" class="form-control" placeholder="Enter a trading name" value="{{$user['account_detail']['0']->trading_name}}"/>
                                  @else
                                      <input type="text" name="trading_name" class="form-control" placeholder="Enter a trading name"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Trading name</span>
                              </div>

                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Account Manager:</label>
                                  @if(isset($user['account_detail']['0']->account_manager))
                                      <input type="text" name="account_manager" class="form-control" placeholder="Enter your Account manager" value="{{$user['account_detail']['0']->account_manager}}"/>
                                  @else
                                      <input type="text" name="account_manager" class="form-control" placeholder="Enter your Account manager"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Account manager</span>
                              </div>
                                  <div class="col-lg-2">
                                      <label>Account status:</label>
                                      <div>
                                          @if(isset($user['account_detail']['0']->account_status))
                                              <select class="form-control select2" id="kt_select2_2" name="account_status">
                                                  @if($user['account_detail']['0']->account_status == 'active')
                                                      <option selected value="active">Active</option>
                                                      <option value="closed">Closed</option>
                                                      <option value="on_hold">On hold</option>
                                                  @elseif($user['account_detail']['0']->account_status == 'closed')
                                                      <option value="active">Active</option>
                                                      <option selected value="closed">Closed</option>
                                                      <option value="on_hold">On hold</option>
                                                  @else
                                                      <option value="active">Active</option>
                                                      <option value="closed">Closed</option>
                                                      <option selected value="on_hold">On hold</option>
                                                  @endif
                                              </select>
                                          @else
                                              <select class="form-control select2" id="kt_select2_2" name="account_status">
                                                      <option value="active">Active</option>
                                                      <option value="closed">Closed</option>
                                                      <option value="on_hold">On hold</option>
                                              </select>
                                          @endif

                                      </div>
                                  </div>
                              <div class="col-lg-4">
                                  <label>Credit limit:</label>
                                  @if(isset($user['account_detail']['0']->credit_limit))
                                      <input type="text" name="credit_limit" class="form-control" placeholder="Enter your credit limit" value="{{$user['account_detail']['0']->credit_limit}}"/>
                                      @else
                                      <input type="text" name="credit_limit" class="form-control" placeholder="Enter your credit limit"/>
                                      @endif
                                  <span class="form-text text-muted">Please enter your credit limit</span>
                              </div>
                              </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>ABN:</label>
                                  @if(isset($user['account_detail']['0']->ABN))
                                      <input type="text" name="abn" class="form-control" placeholder="Enter ABN" value="{{$user['account_detail']['0']->ABN}}"/>
                                  @else
                                      <input type="text" name="abn" class="form-control" placeholder="Enter ABN"/>
                                  @endif
                                  <span class="form-text text-muted">Please enter ABN</span>
                              </div>
                              <div class="col-lg-6">
                                  <label>ACN:</label>
                                  @if(isset($user['account_detail']['0']->ACN))
                                      <input type="text" name="acn" class="form-control" placeholder="Enter your ACN" value="{{$user['account_detail']['0']->ACN}}"/>
                                  @else
                                      <input type="text" name="acn" class="form-control" placeholder="Enter your ACN"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter ACN</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-6">
                                  <label>Account Code:</label>
                                  @if(isset($user['account_detail']['0']->account_code))
                                      <input type="text" name="account_code" class="form-control" placeholder="Enter your Account Code" value="{{$user['account_detail']['0']->account_code}}"/>
                                  @else
                                      <input type="text" name="account_code" class="form-control" placeholder="Enter your Account Code"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Account code</span>
                              </div>
                              <div class="col-lg-2">
                                  <label>Industry:</label>
                                  <div>
                                      @if(isset($user['account_detail']['0']->industry))
                                          <select class="form-control select2" id="kt_select2_3" name="industry">
                                              <option value="{{$user['account_detail']['0']->industry}}">{{$user['account_detail']['0']->industry}}</option>
                                              <option value="agriculture">Agriculture</option>
                                              <option value="automotive">Automotive</option>
                                              <option value="defense">Defence</option>
                                              <option value="education">Education</option>
                                              <option value="environment">Environment</option>
                                          </select>
                                      @else
                                          <select class="form-control select2" id="kt_select2_3" name="industry">
                                              <option value="agriculture">Agriculture</option>
                                              <option value="automotive">Automotive</option>
                                              <option value="defense">Defence</option>
                                              <option value="education">Education</option>
                                              <option value="environment">Environment</option>
                                          </select>
                                      @endif

                                  </div>
                              </div>
                              <div class="col-lg-2">
                                  <label>Payment terms:</label>
                                  <div>
                                      @if(isset($user['account_detail']['0']->payment_terms))
                                          <select class="form-control select2" id="payment_terms" name="payment_term">
                                              <option value="{{$user['account_detail']['0']->payment_terms}}">{{$user['account_detail']['0']->payment_terms}}</option>
                                              <option value="cod">COD</option>
                                              <option value="net 7">Net 7</option>
                                              <option value="net 30">Net 30</option>
                                              <option value="net 21">Net 21</option>
                                              <option value="net 60">Net 60</option>
                                          </select>
                                      @else
                                          <select class="form-control select2" id="payment_terms" name="payment_term">
                                              <option value="cod">COD</option>
                                              <option value="net 7">Net 7</option>
                                              <option value="net 30">Net 30</option>
                                              <option value="net 21">Net 21</option>
                                              <option value="net 60">Net 60</option>
                                          </select>
                                      @endif

                                  </div>
                              </div>
                              <div class="col-lg-2">
                                  <label>Billing method:</label>
                                  <div>
                                      @if(isset($user['account_detail']['0']->billing_method))
                                          <select class="form-control select2" id="billing_method" name="billing_method">
                                              <option value="{{$user['account_detail']['0']->billing_method}}">{{$user['account_detail']['0']->billing_method}}</option>
                                              <option value="cheque">Cheque</option>
                                              <option value="cod">COD</option>
                                              <option value="credit_card">Credit Card</option>
                                          </select>
                                      @else
                                          <select class="form-control select2" id="billing_method" name="billing_method">
                                              <option value="cheque">Cheque</option>
                                              <option value="cod">COD</option>
                                              <option value="credit_card">Credit Card</option>
                                          </select>
                                      @endif

                                  </div>
                              </div>
                          </div>
                          <div class="form-group row">

                              <div class="col-lg-6">
                                  <label>Review Date:</label>
                                  @if(isset($user['account_detail']['0']->review_date))
                                      <input type="date" name="Review_date" class="form-control" placeholder="Enter your Review Date" value="{{$user['account_detail']['0']->review_date}}"/>
                                  @else
                                      <input type="date" name="Review_date" class="form-control" placeholder="Enter your Review Date"/>
                                  @endif

                                  <span class="form-text text-muted">Please enter your Review Date</span>
                              </div>
                          </div>
                      </div>
                      <div class="card-footer">
                          <div class="row">
                              @if(isset($user['account_detail']['0']->business_name))
                                  <div class="col-lg-6">
                                      <button onclick="event.preventDefault(); account_detail_form_action_update();" class="btn btn-primary mr-2">update</button>
                                  </div>
                                  @else
                                  <div class="col-lg-6">
                                      <button onclick="event.preventDefault(); account_detail_form_action();" class="btn btn-primary mr-2">Save</button>
                                  </div>
                                  @endif

                          </div>
                      </div>
                  </form>
              </div>

              <div id="notes" class="tabcontent">
                  <form class="form" id="note_form" method="post" action="{{route('store_notes')}}">

                      @csrf
                      <input type="hidden" value="{{$user->id}}" name="c_id">
                      <input type="hidden" value="" name="c_name">
                      <input type="hidden" value="" name="c_plan">
                      <input type="hidden" value="" name="c_active">

                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Notes: </h3>
                          <div id="note_container">
                              <div class="form-group row">

                                  <div class="col-lg-6">
                                      <label>Add new notes:</label>
                                      <textarea id="note_area" class="form-control" name="note[]"></textarea>
                                  </div>
                                  <div class="col-lg-2">
                                      <label>{{auth()->user()->name}}</label>
                                      <input type="hidden" name="author_name[]" value="{{auth()->user()->name}}">
                                  </div>
                                  <div class="col-lg-2">
                                      <label>{{date("Y/m/d")}}</label>
                                      <input type="hidden" name="date[]" value="{{date("Y/m/d")}}">
                                  </div>
                                  <div class="col-lg-2">
                                      <label>Remove</label>
                                  </div>

                              </div>
                          </div>

                          <div class="form-group row">
                              <div class="col-lg-12">
                                  <a href="#"  onclick="add_note()" class="btn btn-success btn-sm mr-3 float-left ">
                                      <i class="flaticon2-pie-chart"></i>Add new note</a>
                              </div>
                          </div>
                      </div>

                      <div class="card-footer">
                          <div class="row">
                              <div class="col-lg-6">
                                  <button onclick="event.preventDefault(); note_form_action();" class="btn btn-primary mr-2">Save</button>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>

              <div id="attachments" class="tabcontent">
                  <form class="form" id="attachement_form" method="post" action="{{route('store_customer_file')}}" enctype="multipart/form-data">

                      @csrf
                      <input type="hidden" value="{{$user->id}}" name="c_id">
                      <input type="hidden" value="" name="c_name">
                      <input type="hidden" value="" name="c_plan">
                      <input type="hidden" value="" name="c_active">
                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Attachments: </h3>
                          <div class="form-group row">

                              <div class="col-lg-6">
                                  <label>Add a new file:</label>
                                  <input type="file" id="c_file" name="file" class="form-control"/>
                                  <span class="form-text text-muted">Files must be less than 2 MB.Allowed file types: txt pdf xls doc docx jpeg jpg msg xlsx.</span>
                              </div>


                          </div>
                      </div>

                      <div class="card-footer">
                          <div class="row">
                              <div class="col-lg-6">
                                  <button onclick="event.preventDefault(); attachment_form_action();" class="btn btn-primary mr-2">Save</button>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>

              <div id="sites" class="tabcontent">
                  <form class="form" id="site_form" method="post" action="{{route('store_customer_site')}}">
                      @csrf
                      <input type="hidden" value="{{$user->id}}" name="c_id">
                      <input type="hidden" value="" name="c_name">
                      <input type="hidden" value="" name="c_plan">
                      <input type="hidden" value="" name="c_active">

                      <div class="card-body">
                          <h3 class="text-dark font-weight-bold mb-10">Site: </h3>

                          <div id="site_container">
                              <div>
                                  <div class="form-group row">
                                      <div class="col-lg-6">
                                          <label>Site Name:</label>
                                          <input type="text" name="site_name[]" class="form-control" placeholder="Enter a Site name"/>
                                          <span class="form-text text-muted">Please enter your site name</span>
                                      </div>
                                      <div class="col-lg-6">
                                          <label>Address line 1:</label>
                                          <input type="text" name="address_line_1[]" class="form-control" placeholder="Enter a location"/>
                                          <span class="form-text text-muted">Please enter your address line one</span>
                                      </div>
                                  </div>

                                  <div class="form-group row">
                                      <div class="col-lg-6">
                                          <label>Address line 2:</label>
                                          <input type="text" name="address_line_2[]" class="form-control" placeholder="Enter a location"/>
                                          <span class="form-text text-muted">Please enter your address line two</span>
                                      </div>
                                      <div class="col-lg-6">
                                          <label>Suburb:</label>
                                          <input type="text" name="suburb[]" class="form-control" placeholder="Enter your suburb"/>
                                          <span class="form-text text-muted">Please enter suburb</span>
                                      </div>
                                  </div>

                                  <div class="form-group row">
                                      <div class="col-lg-6">
                                          <label>Postal code:</label>
                                          <input type="text" name="postal_code[]" class="form-control" placeholder="Enter a postal code"/>
                                          <span class="form-text text-muted">Please enter your postal code</span>
                                      </div>
                                      <div class="col-lg-3">
                                          <label>State:</label>
                                          <input type="text" name="state[]" class="form-control" placeholder="Enter your state"/>
                                          <span class="form-text text-muted">Please enter state</span>
                                      </div>
                                      <div class="col-lg-3">
                                          <label>Opening time:</label>
                                          <input type="time" name="opening_time[]" class="form-control" placeholder="Enter your opening time"/>
                                          <span class="form-text text-muted">Please enter opening time</span>
                                      </div>
                                  </div>
                              </div>
                          </div>


                          <div class="form-group row">
                              <div class="col-lg-12">
                                  <a href="#" onclick="add_site()" class="btn btn-success btn-sm mr-3 float-right ">
                                      <i class="flaticon2-pie-chart"></i>Add new site</a>
                              </div>
                          </div>
                      </div>

                      <div class="card-footer">
                          <div class="row">
                              <div class="col-lg-6">
                                  <button onclick="event.preventDefault(); site_form_action();" class="btn btn-primary mr-2">Save</button>
                              </div>
                          </div>
                      </div>

                  </form>
              </div>

            <!--end::Form-->
          </div>
        </div>
        <!--end::Card-->

      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->
  </div>
@endsection
@section('script')
    <script>

        function contact_form_action(){

            var contact_name = $('input[name="p_contact_name"]').val();
            if(contact_name == ""){
                alert('primary name is required')
            }else{
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('contact_form').submit();
                }else{
                    alert('customer name required');
                }
            }
        }
        function address_form_action() {
            var p_address_line_1 = $('input[name="p_address_line_1"]').val();
            var p_postal_code = $('input[name="p_postal_code"]').val();
            if(p_address_line_1 && p_postal_code){
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('address_form').submit();
                }else{
                    alert('customer name required');
                }

            }else{
                alert('location and primary postal code is required')
            }
        }
        function account_detail_form_action() {
            var business_name = $('input[name="business_name"]').val();
            if(business_name){
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('account_detail_form').submit();
                }else{
                    alert('customer name required');
                }

            }else{
                alert('business name is required')
            }
        }
        function note_form_action() {
            var note = $('#note_area').val();
            if(note){
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('note_form').submit();
                }else{
                    alert('customer name required');
                }

            }else{
                alert('note is required')
            }
        }
        function attachment_form_action() {
            var file = $('#c_file').val();
            if(file){
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('attachement_form').submit();
                }else{
                    alert('customer name required');
                }

            }else{
                alert('file is required')
            }
        }
        function site_form_action() {
            var site_name = $('input[name="site_name[]"]').val();
            if(site_name){
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('site_form').submit();
                }else{
                    alert('customer name required');
                }

            }else{
                alert('site name is required')
            }
        }
        function add_note(){
            var note_row = '                   <div class="form-group row"><div class="col-lg-6">\n' +
                '                                      <textarea class="form-control" name="note[]"></textarea>\n' +
                '                                  </div>\n' +
                '                                  <div class="col-lg-2">\n' +
                '                                      <label>{{auth()->user()->name}}</label>\n' +
                '                                       <input type="hidden" name="author_name[]" value="{{auth()->user()->name}}">\n'+
                '                                  </div>\n' +
                '                                  <div class="col-lg-2">\n' +
                '                                      <label>{{date("Y/m/d")}}</label>\n' +
                '                                      <input type="hidden" name="date[]" value="{{date("Y/m/d")}}">\n'+
                '                                  </div>\n' +
                '                                  <div class="col-lg-2">\n' +
                '                                      <div>\n' +
                '                                          <a href="#" class="remove-note"><span style="color: red">X</span></a>\n' +
                '                                      </div>\n' +
                '                              </div></div>';
            $('#note_container').append(note_row);
        }
        function add_site(){

            var site_row = '<div><hr><hr><h3 class="text-dark font-weight-bold mb-10">Site: </h3>\n' +
                '                                  <div class="form-group row">\n' +
                '                                      <div class="col-lg-6">\n' +
                '                                          <label>Site Name:</label>\n' +
                '                                          <input type="text" name="site_name[]" class="form-control" placeholder="Enter a Site name"/>\n' +
                '                                          <span class="form-text text-muted">Please enter your site name</span>\n' +
                '                                      </div>\n' +
                '                                      <div class="col-lg-6">\n' +
                '                                          <label>Address line 1:</label>\n' +
                '                                          <input type="text" name="address_line_1[]" class="form-control" placeholder="Enter a location"/>\n' +
                '                                          <span class="form-text text-muted">Please enter your address line one</span>\n' +
                '                                      </div>\n' +
                '                                  </div>\n' +
                '\n' +
                '                                  <div class="form-group row">\n' +
                '                                      <div class="col-lg-6">\n' +
                '                                          <label>Address line 2:</label>\n' +
                '                                          <input type="text" name="address_line_2[]" class="form-control" placeholder="Enter a location"/>\n' +
                '                                          <span class="form-text text-muted">Please enter your address line two</span>\n' +
                '                                      </div>\n' +
                '                                      <div class="col-lg-6">\n' +
                '                                          <label>Suburb:</label>\n' +
                '                                          <input type="text" name="suburb[]" class="form-control" placeholder="Enter your suburb"/>\n' +
                '                                          <span class="form-text text-muted">Please enter suburb</span>\n' +
                '                                      </div>\n' +
                '                                  </div>\n' +
                '\n' +
                '                                  <div class="form-group row">\n' +
                '                                      <div class="col-lg-6">\n' +
                '                                          <label>Postal code:</label>\n' +
                '                                          <input type="text" name="postal_code[]" class="form-control" placeholder="Enter a postal code"/>\n' +
                '                                          <span class="form-text text-muted">Please enter your postal code</span>\n' +
                '                                      </div>\n' +
                '                                      <div class="col-lg-3">\n' +
                '                                          <label>State:</label>\n' +
                '                                          <input type="text" name="state[]" class="form-control" placeholder="Enter your state"/>\n' +
                '                                          <span class="form-text text-muted">Please enter state</span>\n' +
                '                                      </div>\n' +
                '                                      <div class="col-lg-3">\n' +
                '                                          <label>Opening time:</label>\n' +
                '                                          <input type="time" name="opening_time[]" class="form-control" placeholder="Enter your opening time"/>\n' +
                '                                          <span class="form-text text-muted">Please enter opening time</span>\n' +
                '                                      </div>\n' +
                '                                  </div>\n' +
                '                                  <div class="col-lg-2">\n' +
                '                                      <div>\n' +
                '                                          <a href="#" class="remove-site"><span style="color: red">Remove</span></a>\n' +
                '                                      </div>\n' +
                '                              </div></div>';

            $('#site_container').append(site_row);

        }


        function contact_form_action_update(){
            var contact_name = $('input[name="p_contact_name"]').val();
            if(contact_name == ""){
                alert('primary name is required')
            }else{
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('contact_form').submit();
                }else{
                    alert('customer name required');
                }
            }
        }


        function address_form_action_update(){
            var p_address_line_1 = $('input[name="p_address_line_1"]').val();
            var p_postal_code = $('input[name="p_postal_code"]').val();
            if(p_address_line_1 && p_postal_code){
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('address_form').submit();
                }else{
                    alert('customer name required');
                }

            }else{
                alert('location and primary postal code is required')
            }
        }


        function account_detail_form_action_update(){
            var business_name = $('input[name="business_name"]').val();
            if(business_name){
                var customer_name = $('input[name="name"]').val();
                var customer_plan = $('#kt_select2_1').val();
                var customer_active = $('input[name="active"]').val();

                if(customer_name){
                    $('input[name="c_name"]').val(customer_name);
                    $('input[name="c_plan"]').val(customer_plan);
                    $('input[name="c_active"]').val(customer_active);
                    document.getElementById('account_detail_form').submit();
                }else{
                    alert('customer name required');
                }

            }else{
                alert('business name is required')
            }
        }


        $("body").on("click",".remove-site",function () {
            $(this).parent().parent().parent().remove();
        });

        $("body").on("click",".remove-note",function () {
            $(this).parent().parent().parent().remove();
        });

        var avatar1 = new KTImageInput('kt_image_1');

        $(document).ready(function(){
            openCity(event, 'contact');
        });

        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        // Class definition
        var KTSelect2 = function() {
            // Private functions
            var demos = function() {
                // basic
                $('#kt_select2_1').select2({
                    placeholder: "Select a state"
                });

                // nested
                $('#kt_select2_2').select2({
                    placeholder: "Select a state"
                });

                // multi select
                $('#kt_select2_3').select2({
                    placeholder: "Select a state",
                });
                $('#payment_terms').select2({
                    placeholder: "Select a state",
                });

                $('#billing_method').select2({
                   placeholder:"Select method",
                });

                // basic
                $('#kt_select2_4').select2({
                    placeholder: "Select a state",
                    allowClear: true
                });

                // loading data from array
                var data = [{
                    id: 0,
                    text: 'Enhancement'
                }, {
                    id: 1,
                    text: 'Bug'
                }, {
                    id: 2,
                    text: 'Duplicate'
                }, {
                    id: 3,
                    text: 'Invalid'
                }, {
                    id: 4,
                    text: 'Wontfix'
                }];

                $('#kt_select2_5').select2({
                    placeholder: "Select a value",
                    data: data
                });

                // loading remote data

                function formatRepo(repo) {
                    if (repo.loading) return repo.text;
                    var markup = "<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
                    if (repo.description) {
                        markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }
                    markup += "<div class='select2-result-repository__statistics'>" +
                        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
                        "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
                        "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
                        "</div>" +
                        "</div></div>";
                    return markup;
                }

                function formatRepoSelection(repo) {
                    return repo.full_name || repo.text;
                }

                $("#kt_select2_6").select2({
                    placeholder: "Search for git repositories",
                    allowClear: true,
                    ajax: {
                        url: "https://api.github.com/search/repositories",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function(data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            params.page = params.page || 1;

                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    }, // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: formatRepo, // omitted for brevity, see the source of this page
                    templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
                });

                // custom styles

                // tagging support
                $('#kt_select2_12_1, #kt_select2_12_2, #kt_select2_12_3, #kt_select2_12_4').select2({
                    placeholder: "Select an option",
                });

                // disabled mode
                $('#kt_select2_7').select2({
                    placeholder: "Select an option"
                });

                // disabled results
                $('#kt_select2_8').select2({
                    placeholder: "Select an option"
                });

                // limiting the number of selections
                $('#kt_select2_9').select2({
                    placeholder: "Select an option",
                    maximumSelectionLength: 2
                });

                // hiding the search box
                $('#kt_select2_10').select2({
                    placeholder: "Select an option",
                    minimumResultsForSearch: Infinity
                });

                // tagging support
                $('#kt_select2_11').select2({
                    placeholder: "Add a tag",
                    tags: true
                });

                // disabled results
                $('.kt-select2-general').select2({
                    placeholder: "Select an option"
                });
            }

            var modalDemos = function() {
                $('#kt_select2_modal').on('shown.bs.modal', function () {
                    // basic
                    $('#kt_select2_1_modal').select2({
                        placeholder: "Select a state"
                    });

                    // nested
                    $('#kt_select2_2_modal').select2({
                        placeholder: "Select a state"
                    });

                    // multi select
                    $('#kt_select2_3_modal').select2({
                        placeholder: "Select a state",
                    });

                    // basic
                    $('#kt_select2_4_modal').select2({
                        placeholder: "Select a state",
                        allowClear: true
                    });
                });
            }

            // Public functions
            return {
                init: function() {
                    demos();
                    modalDemos();
                }
            };
        }();

        // Initialization
        jQuery(document).ready(function() {
            KTSelect2.init();
        });
    </script>

@endsection
@section('style')
<style>
    body {font-family: Arial;}

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #edeaf4;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 15px;
        font-family: "Nunito", sans-serif;

    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>
@endsection
