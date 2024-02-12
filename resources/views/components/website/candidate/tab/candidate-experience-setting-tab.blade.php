@props(['experiences'])
<div class="dashboard-right-header rt-mb-32 lg:tw-mt-0 justify-content-between">
    <h3 class="f-size-18 lh-1 m-0">{{ __('experience') }}</h3>
    <button id="addExperience" type="button" class="btn btn-primary">
        {{ __('add_experience') }}
    </button>
</div>
<div class="db-job-card-table">
    <table>
        <thead>
            <tr>
                <th>{{ __('company') }}</th>
                <th>{{ __('department') }}</th>
                <th>{{ __('designation') }}</th>
                <th>{{ __('period') }}</th>
                <th>{{ __('action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($experiences as $experience)
                <tr>
                    <td>{{ $experience->company }}</td>
                    <td>{{ $experience->department }}</td>
                    <td>{{ $experience->designation }}</td>
                    <td>
                        {{ formatTime($experience->start, 'd M Y') }} - {{ formatTime($experience->end, 'd M Y') }}
                    </td>
                    <td>
                        <div class=" d-flex justify-content-center">
                            <button type="button" class="btn btn-icon" id="dropdownMenuButton5"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 13.125C12.6213 13.125 13.125 12.6213 13.125 12C13.125 11.3787 12.6213 10.875 12 10.875C11.3787 10.875 10.875 11.3787 10.875 12C10.875 12.6213 11.3787 13.125 12 13.125Z"
                                        fill="#767F8C" stroke="#767F8C" />
                                    <path
                                        d="M12 6.65039C12.6213 6.65039 13.125 6.14671 13.125 5.52539C13.125 4.90407 12.6213 4.40039 12 4.40039C11.3787 4.40039 10.875 4.90407 10.875 5.52539C10.875 6.14671 11.3787 6.65039 12 6.65039Z"
                                        fill="#767F8C" stroke="#767F8C" />
                                    <path
                                        d="M12 19.6094C12.6213 19.6094 13.125 19.1057 13.125 18.4844C13.125 17.8631 12.6213 17.3594 12 17.3594C11.3787 17.3594 10.875 17.8631 10.875 18.4844C10.875 19.1057 11.3787 19.6094 12 19.6094Z"
                                        fill="#767F8C" stroke="#767F8C" />
                                </svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end company-dashboard-dropdown"
                                aria-labelledby="dropdownMenuButton5">
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="experienceDetail({{ json_encode($experience) }}, '{{ date('d-m-Y', strtotime($experience->start)) }}', '{{ date('d-m-Y', strtotime($experience->end)) }}')">
                                        <x-svg.edit-icon/>
                                        {{ __('edit') }}
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('candidate.experiences.destroy', $experience->id) }}">
                                        @csrf
                                        @method('Delete')
                                        <button type="submit" class="dropdown-item" onclick="return confirm('{{ __('are_you_sure_you_want_to_delete_this_item') }}');">
                                            <x-svg.trash-icon/>
                                            {{ __('delete') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        <x-svg.not-found-icon />
                        <p class="mt-4">{{ __('no_data_found') }}</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Add Experience Modal --}}
<div class="modal fade" id="addExperienceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('candidate.experiences.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <h5 class="modal-title rt-mb-18 f-size-18" id="cvModalLabel">{{ __('add_experience') }}</h5>
                    <div class="from-group rt-mb-18">
                        <x-forms.label name="company" class="rt-mb-8" />
                        <input type="text" name="company" required class="@error('company') is-invalid @enderror" placeholder="{{ __('enter') }} {{ __('company') }}">

                        @error('company')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-6">
                            <x-forms.label name="department" class="rt-mb-8" />
                            <input type="text" name="department" required placeholder="{{ __('enter') }} {{ __('department') }}">
                        </div>
                        <div class="col-lg-6">
                            <x-forms.label name="designation" class="rt-mb-8" />
                            <input type="text" name="designation" required placeholder="{{ __('enter') }} {{ __('designation') }}">
                        </div>
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-6">
                            <x-forms.label name="start_date" class="rt-mb-8"/>
                             <input type="text" name="start" value="{{ old('start') }}" placeholder="d/m/y" class="date_picker form-control border-cutom @error('start') is-invalid @enderror" required>
                        </div>
                        <div class="col-lg-6 experience_end_date">
                            <x-forms.label name="end_date" class="rt-mb-8" />
                            <input type="text" name="end" value="{{ old('end') }}" placeholder="d/m/y" class="date_picker form-control border-cutom @error('end') is-invalid @enderror" required>
                        </div>
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-12">
                            <x-forms.label name="responsibilities" class="rt-mb-8" :required="false"/>
                            <textarea class="form-control @error('responsibilities') is-invalid @enderror" placeholder="{{ __('enter') }} {{ __('responsibilities') }}" name="responsibilities" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="bg-priamry-50 btn btn-primary-50" onclick="closeAddExperienceModal()">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="button-content-wrapper ">
                                <span class="button-icon align-icon-right"><i class="ph-arrow-right"></i></span>
                                <span class="button-text">
                                    {{ __('add_experience') }}
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn-close" onclick="closeAddExperienceModal()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.75 5.25L5.25 18.75" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M18.75 18.75L5.25 5.25" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>


{{-- Edit Experience Modal --}}
<div class="modal fade" id="editExperienceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('candidate.experiences.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <h5 class="modal-title rt-mb-18 f-size-18" id="cvModalLabel">{{ __('edit_experience') }}</h5>
                    <input type="hidden" name="experience_id" id="experience-modal-id">
                    <div class="from-group rt-mb-18">
                        <x-forms.label name="company" class="rt-mb-8" />
                        <input id="experience-modal-company" type="text" name="company" required placeholder="{{ __('enter') }} {{ __('company') }}">
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-6">
                            <x-forms.label name="department" class="rt-mb-8" />
                            <input id="experience-modal-department" type="text" name="department" required placeholder="{{ __('enter') }} {{ __('department') }}">
                        </div>
                        <div class="col-lg-6">
                            <x-forms.label name="designation" class="rt-mb-8" />
                            <input id="experience-modal-designation" type="text" name="designation" required placeholder="{{ __('enter') }} {{ __('designation') }}">
                        </div>
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-6">
                            <x-forms.label name="start_date" class="rt-mb-8"/>
                             <input id="experience-modal-start" type="text" name="start" value="{{ old('start') }}" placeholder="d/m/y" class="date_picker form-control border-cutom @error('start') is-invalid @enderror" required>
                        </div>
                        <div class="col-lg-6 experience_end_date">
                            <x-forms.label name="end_date" class="rt-mb-8" />
                            <input id="experience-modal-end" type="text" name="end" value="{{ old('end') }}" placeholder="d/m/y" class="date_picker form-control border-cutom @error('end') is-invalid @enderror" required>
                        </div>
                    </div>
                    <div class="row rt-mb-18">
                        <div class="col-lg-12">
                            <x-forms.label name="responsibilities" class="rt-mb-8" :required="false"/>
                            <textarea id="experience-responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" placeholder="{{ __('enter') }} {{ __('responsibilities') }}" name="responsibilities" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="bg-priamry-50 btn btn-primary-50" onclick="closeEditExperienceModal()">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="button-content-wrapper ">
                                <span class="button-icon align-icon-right"><i class="ph-arrow-right"></i></span>
                                <span class="button-text">
                                    {{ __('update_experience') }}
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn-close" onclick="closeEditExperienceModal()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.75 5.25L5.25 18.75" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M18.75 18.75L5.25 5.25" stroke="#0A65CC" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>

@push('frontend_links')
<link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/bootstrap-datepicker.min.css">

@endpush

@push('frontend_scripts')
<script src="{{ asset('frontend/assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script>

        $('#addExperience').on('click', function(){
            $('#addExperienceModal').modal('show');
        });

         $(".date_picker").attr("autocomplete", "off");

        //init datepicker
        $('.date_picker').off('focus').datepicker({
            format: 'd-m-yyyy',
        }).on('click',
            function() {
                $(this).datepicker('show');
            }
        );

        function closeAddExperienceModal(){
            $('#addExperienceModal').find('form')[0].reset();
            $('#addExperienceModal').modal('hide')
        }

        function closeEditExperienceModal(){
            $('#editExperienceModal').find('form')[0].reset();
            $('#editExperienceModal').modal('hide')
        }

        function experienceDetail(experience, start, end) {
            $('#experience-modal-id').val(experience.id);
            $('#experience-modal-company').val(experience.company);
            $('#experience-modal-department').val(experience.department);
            $('#experience-modal-designation').val(experience.designation);
            $('#experience-modal-start').val(start);
            $('#experience-modal-end').val(end);
            $('#experience-responsibilities').val(experience.responsibilities);

            $('#editExperienceModal').modal('show');
        }
    </script>
@endpush
