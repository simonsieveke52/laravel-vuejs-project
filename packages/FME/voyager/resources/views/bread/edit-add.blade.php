@extends('voyager::master')

@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="col-12">
                    <h1 class="page-title">
                        <i class="h3 mb-0 {{ $dataType->icon }}"></i> {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="page-content edit-add container-fluid pb-5">
        <div class="row">
            <div class="col-12">
                <div class="col-12">
                    <div class="panel panel-bordered">
                        <!-- form start -->
                        <form 
                            role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data"
                        >
                            <!-- CSRF TOKEN -->
                            @csrf

                            <!-- PUT Method if we are editing -->
                            @if($edit)
                                @method('PUT')
                            @endif

                            <div class="panel-body row">

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Adding / Editing -->
                                @php
                                    $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )}->sortByDesc('type');
                                @endphp

                                @foreach($dataTypeRows as $row)

                                    {{-- Continue if relationship details doesnt exist --}}
                                    @continue($row->type == 'relationship' && (! isset($row->details->model) || ! isset($row->details->type)))

                                    @if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')})
                                        @php
                                            $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                        @endphp
                                    @endif

                                    @if (isset($row->details->legend) && isset($row->details->legend->text))
                                        <legend 
                                            class="text-{{ $row->details->legend->align ?? 'center' }}" 
                                            style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }}; padding: 5px;">
                                            {{ $row->details->legend->text }}
                                        </legend>
                                    @endif

                                    <div 
                                        class="
                                            d-flex flex-column align-items-start form-group col-12 
                                            {{ $row->display_options->width ?? $row->display_crud_options->class ?? 'col-lg-4' }} 
                                            @if($row->type == 'hidden') d-none @endif 
                                            {{ $errors->has($row->field) ? 'has-error' : '' }}
                                        "
                                        @if(isset($row->display_options->id)) {{ "id=$row->display_options->id" }} @endif
                                    >

                                        {{ $row->slugify }}

                                        <label class="control-label" for="name">{{ $row->display_name }}</label>

                                        @include('voyager::multilingual.input-hidden-bread-edit-add')

                                        @if (isset($row->details->view))
                                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                        @elseif ($row->type == 'relationship')
                                            @include('voyager::formfields.relationship', ['options' => $row->details])
                                        @else
                                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                        @endif

                                        @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                        @endforeach

                                        @if (isset($row->display_options->text_helper))
                                            <small class="d-block mt-2 text-muted">
                                                {{ $row->display_options->text_helper }}
                                            </small>
                                        @endif

                                        @if ($errors->has($row->field))
                                            @foreach ($errors->get($row->field) as $error)
                                                <span class="d-block help-block">{{ $error }}</span>
                                            @endforeach
                                        @endif

                                    </div>

                                @endforeach

                            </div><!-- panel-body -->

                            <div class="panel-footer text-right">
                                @section('submit-buttons')
                                    <button type="submit" class="btn btn-pink save min-w-120px">{{ __('voyager::generic.save') }}</button>
                                @stop

                                @if (url()->previous() !== url()->current())
                                    <a href="{{ url()->previous() }}" class="btn btn-default save min-w-120px">Back</a>
                                @endif

                                @yield('submit-buttons')
                            </div>
                        </form>

                        <iframe id="form_target" name="form_target" style="display:none"></iframe>
                        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                                enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                            <input name="image" id="upload_file" type="file"
                                     onchange="$('#my_form').submit();this.value='';">
                            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                            {{ csrf_field() }}
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
