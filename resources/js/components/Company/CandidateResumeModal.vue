<template>
    <transition name="fade">
        <div v-if="show"
            class="tw-fixed tw-top-0 tw-left-0 tw-right-0 tw-z-50 tw-p-4 tw-overflow-y-auto md:tw-inset-0 h-modal md:tw-h-full tw-flex tw-items-center tw-justify-center tw-bg-gray-800 tw-bg-opacity-90 modal fade show"
            id="candidate-profile-modal" style="display: block;">
            <div class="modal-dialog  modal-wrapper">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-5">
                            <div class="col-md-8">
                                <div class="candidate-profile mb-4 mb-md-0">
                                    <div class="candidate-profile-img">
                                        <img v-if="candidate.photo" :src="candidate.photo" alt="">
                                    </div>
                                    <div class="candidate-profile-info">
                                        <h2 class="name">{{ data.name }}</h2>
                                        <h4 class="designation">
                                            {{ candidate.profession ? candidate.profession.name:'N/A' }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="biography-wrap">
                                    <input id="candidate_id" type="hidden" value="">
                                    <div class="biography">
                                        <h2 class="title">{{ candidate.bio ? candidate.bio: 'N/A' }}</h2>
                                    </div>
                                    <div
                                        v-if="social.facebook || social.twitter || social.linkedin || social.youtube || social.instagram">
                                        <div class="devider"></div>
                                        <div class="social-links">
                                            <h2 class="title">{{ __('follow_me_social_media') }}</h2>
                                            {{ social }}
                                            <div class="social-media">
                                                <ul>
                                                    <li v-if="social.facebook">
                                                        <a :href="social.facebook"
                                                            class="bg-primary-50 text-primary-500 plain-button icon-56 hover:bg-primary-500 hover:text-primary-50">

                                                            <svg width="20" height="20" viewBox="0 0 21 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M12.1666 20H8.20126V10.1414H5.5V6.9316H8.20116V4.64762C8.20116 1.9411 9.39588 0 13.3505 0C14.1869 0 15.5 0.168134 15.5 0.168134V3.14858H14.1208C12.7155 3.14858 12.1668 3.5749 12.1668 4.75352V6.9316H15.4474L15.1553 10.1414H12.1667L12.1666 20Z"
                                                                    fill="currentColor" />
                                                            </svg>

                                                        </a>
                                                    </li>
                                                    <li v-if="social.twitter">
                                                        <a :href="social.twitter"
                                                            class="bg-primary-50 text-primary-500 plain-button icon-56 hover:bg-primary-500 hover:text-primary-50">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                aria-hidden="true" role="img" iconify--bx width="24"
                                                                height="24" preserveAspectRatio="xMidYMid meet"
                                                                viewBox="0 0 24 24" data-icon="bx:bxl-twitter">
                                                                <path
                                                                    d="M19.633 7.997c.013.175.013.349.013.523c0 5.325-4.053 11.461-11.46 11.461c-2.282 0-4.402-.661-6.186-1.809c.324.037.636.05.973.05a8.07 8.07 0 0 0 5.001-1.721a4.036 4.036 0 0 1-3.767-2.793c.249.037.499.062.761.062c.361 0 .724-.05 1.061-.137a4.027 4.027 0 0 1-3.23-3.953v-.05c.537.299 1.16.486 1.82.511a4.022 4.022 0 0 1-1.796-3.354c0-.748.199-1.434.548-2.032a11.457 11.457 0 0 0 8.306 4.215c-.062-.3-.1-.611-.1-.923a4.026 4.026 0 0 1 4.028-4.028c1.16 0 2.207.486 2.943 1.272a7.957 7.957 0 0 0 2.556-.973a4.02 4.02 0 0 1-1.771 2.22a8.073 8.073 0 0 0 2.319-.624a8.645 8.645 0 0 1-2.019 2.083z"
                                                                    fill="currentColor"></path>
                                                            </svg>

                                                        </a>
                                                    </li>
                                                    <li v-if="social.linkedin">
                                                        <a :href="social.linkedin"
                                                            class="bg-primary-50 text-primary-500 plain-button icon-56 hover:bg-primary-500 hover:text-primary-50">
                                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0 2.66051C0 2.01699 0.225232 1.48611 0.675676 1.06784C1.12612 0.649561 1.71172 0.44043 2.43243 0.44043C3.14029 0.44043 3.71299 0.646337 4.15058 1.05819C4.60102 1.4829 4.82625 2.0363 4.82625 2.71842C4.82625 3.33618 4.60747 3.85097 4.16988 4.26282C3.71944 4.68753 3.12741 4.89989 2.39382 4.89989H2.37452C1.66666 4.89989 1.09396 4.68753 0.656371 4.26282C0.218784 3.83811 0 3.304 0 2.66051ZM0.250965 19.5524V6.65664H4.53668V19.5524H0.250965ZM6.9112 19.5524H11.1969V12.3516C11.1969 11.9012 11.2484 11.5537 11.3514 11.3092C11.5315 10.8716 11.805 10.5015 12.1718 10.1991C12.5386 9.89666 12.9987 9.74545 13.5521 9.74545C14.9936 9.74545 15.7143 10.7171 15.7143 12.6605V19.5524H20V12.1586C20 10.2538 19.5496 8.80915 18.6486 7.8246C17.7477 6.84004 16.5573 6.34776 15.0772 6.34776C13.417 6.34776 12.1236 7.06205 11.1969 8.49062V8.52923H11.1776L11.1969 8.49062V6.65664H6.9112C6.93693 7.06848 6.94981 8.34904 6.94981 10.4983C6.94981 12.6476 6.93693 15.6656 6.9112 19.5524Z"
                                                                    fill="var(--primary-500)" />
                                                            </svg>

                                                        </a>
                                                    </li>
                                                    <li v-if="social.youtube">
                                                        <a :href="social.youtube"
                                                            class="bg-primary-50 text-primary-500 plain-button icon-56 hover:bg-primary-500 hover:text-primary-50">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                aria-hidden="true" role="img" width="1em" height="1em"
                                                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"
                                                                data-icon="bx:bxl-instagram">
                                                                <path
                                                                    d="M11.999 7.377a4.623 4.623 0 1 0 0 9.248a4.623 4.623 0 0 0 0-9.248zm0 7.627a3.004 3.004 0 1 1 0-6.008a3.004 3.004 0 0 1 0 6.008z"
                                                                    fill="currentColor"></path>
                                                                <circle cx="16.806" cy="7.207" r="1.078"
                                                                    fill="currentColor"></circle>
                                                                <path
                                                                    d="M20.533 6.111A4.605 4.605 0 0 0 17.9 3.479a6.606 6.606 0 0 0-2.186-.42c-.963-.042-1.268-.054-3.71-.054s-2.755 0-3.71.054a6.554 6.554 0 0 0-2.184.42a4.6 4.6 0 0 0-2.633 2.632a6.585 6.585 0 0 0-.419 2.186c-.043.962-.056 1.267-.056 3.71c0 2.442 0 2.753.056 3.71c.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632a6.584 6.584 0 0 0 2.185.45c.963.042 1.268.055 3.71.055s2.755 0 3.71-.055a6.615 6.615 0 0 0 2.186-.419a4.613 4.613 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.186c.043-.962.056-1.267.056-3.71s0-2.753-.056-3.71a6.581 6.581 0 0 0-.421-2.217zm-1.218 9.532a5.043 5.043 0 0 1-.311 1.688a2.987 2.987 0 0 1-1.712 1.711a4.985 4.985 0 0 1-1.67.311c-.95.044-1.218.055-3.654.055c-2.438 0-2.687 0-3.655-.055a4.96 4.96 0 0 1-1.669-.311a2.985 2.985 0 0 1-1.719-1.711a5.08 5.08 0 0 1-.311-1.669c-.043-.95-.053-1.218-.053-3.654c0-2.437 0-2.686.053-3.655a5.038 5.038 0 0 1 .311-1.687c.305-.789.93-1.41 1.719-1.712a5.01 5.01 0 0 1 1.669-.311c.951-.043 1.218-.055 3.655-.055s2.687 0 3.654.055a4.96 4.96 0 0 1 1.67.311a2.991 2.991 0 0 1 1.712 1.712a5.08 5.08 0 0 1 .311 1.669c.043.951.054 1.218.054 3.655c0 2.436 0 2.698-.043 3.654h-.011z"
                                                                    fill="currentColor"></path>
                                                            </svg>

                                                        </a>
                                                    </li>
                                                    <li v-if="social.instagram">
                                                        <a :href="social.instagram"
                                                            class="bg-primary-50 text-primary-500 plain-button icon-56 hover:bg-primary-500 hover:text-primary-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                aria-hidden="true" role="img" iconify--bx width="1em"
                                                                height="1em" preserveAspectRatio="xMidYMid meet"
                                                                viewBox="0 0 24 24" data-icon="bx:bxl-youtube">
                                                                <path
                                                                    d="M21.593 7.203a2.506 2.506 0 0 0-1.762-1.766C18.265 5.007 12 5 12 5s-6.264-.007-7.831.404a2.56 2.56 0 0 0-1.766 1.778c-.413 1.566-.417 4.814-.417 4.814s-.004 3.264.406 4.814c.23.857.905 1.534 1.763 1.765c1.582.43 7.83.437 7.83.437s6.265.007 7.831-.403a2.515 2.515 0 0 0 1.767-1.763c.414-1.565.417-4.812.417-4.812s.02-3.265-.407-4.831zM9.996 15.005l.005-6l5.207 3.005l-5.212 2.995z"
                                                                    fill="currentColor"></path>
                                                            </svg>

                                                        </a>
                                                    </li>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="devider"></div>
                                        <div class="social-links"
                                            v-if="candidate.experiences && candidate.experiences.length">
                                            <div class="dashboard-right-header rt-mb-32 lg:tw-mt-0">
                                                <div class="left-text m-0">
                                                    <h2 class="title text-uppercase">{{ __('experiences') }}</h2>
                                                </div>
                                                <span class="sidebar-open-nav">
                                                    <i class="ph-list"></i>
                                                </span>
                                            </div>
                                            <div class="db-job-card-table">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('company') }}</th>
                                                            <th>{{ __('department') }} / {{ __('designation') }}</th>
                                                            <th>{{ __('period') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="candidate_profile_experiences">
                                                        <tr v-for="experience in candidate.experiences"
                                                            :key="experience.id">
                                                            <td>{{ experience.company }}</td>
                                                            <td>{{ experience.department }} /
                                                                {{ experience.designation }}</td>
                                                            <td>{{ experience.formatted_start }} -
                                                                {{ experience.formatted_end }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="social-links"
                                            v-if="candidate.educations && candidate.educations.length">
                                            <div class="dashboard-right-header rt-mb-32 lg:tw-mt-0">
                                                <div class="left-text m-0">
                                                    <h2 class="title text-uppercase">{{ __('educations') }}</h2>
                                                </div>
                                                <span class="sidebar-open-nav">
                                                    <i class="ph-list"></i>
                                                </span>
                                            </div>
                                            <div class="db-job-card-table">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('education_level') }}</th>
                                                            <th>{{ __('degree') }}</th>
                                                            <th>{{ __('year') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="candidate_profile_educations">
                                                        <tr v-for="education in candidate.educations"
                                                            :key="education.id">
                                                            <td>{{ education.level }}</td>
                                                            <td>{{ education.degree }}</td>
                                                            <td>{{ education.year }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="sidebar-widget">
                                    <div class="row">
                                        <div class="col-md-12" v-if="candidate.skills && candidate.skills.length">
                                            <h2 class="title">{{ __('skills') }}</h2>
                                            <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-6">
                                                <span
                                                    class="tw-bg-[#E7F0FA] tw-rounded-[4px] tw-text-sm tw-text-[#0A65CC] tw-px-3 tw-py-1.5"
                                                    v-for="skill in candidate.skills" :key="skill.id">
                                                    {{ skill.name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-12" v-if="candidate.languages && candidate.languages.length">
                                            <h2 class="title">{{ __('languages') }}</h2>
                                            <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-6">
                                                <span
                                                    class="tw-bg-[#E7F0FA] tw-rounded-[4px] tw-text-sm tw-text-[#0A65CC] tw-px-3 tw-py-1.5"
                                                    v-for="language in candidate.languages" :key="language.id">
                                                    {{ language.name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-widget">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="icon-box">
                                                <div class="icon-img">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 8.25V6" stroke="var(--primary-500)"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M12 6C16.3312 4.5 12 0.75 12 0.75C12 0.75 7.5 4.5 12 6Z"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M15.1875 11.8125C15.1875 12.6579 14.8517 13.4686 14.2539 14.0664C13.6561 14.6642 12.8454 15 12 15C11.1546 15 10.3439 14.6642 9.7461 14.0664C9.14832 13.4686 8.8125 12.6579 8.8125 11.8125"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M8.81252 11.8125C8.8127 12.6466 8.4859 13.4476 7.90224 14.0435C7.31859 14.6395 6.5246 14.9828 5.69064 15C3.90002 15.0375 2.43752 13.5375 2.43752 11.7469V10.5C2.43628 10.2042 2.49363 9.91106 2.60626 9.63752C2.7189 9.36397 2.88458 9.11544 3.09376 8.90626C3.30294 8.69708 3.55147 8.5314 3.82502 8.41876C4.09856 8.30613 4.3917 8.24878 4.68752 8.25002H19.3125C19.6083 8.24878 19.9015 8.30613 20.175 8.41876C20.4486 8.5314 20.6971 8.69708 20.9063 8.90626C21.1155 9.11544 21.2811 9.36397 21.3938 9.63752C21.5064 9.91106 21.5638 10.2042 21.5625 10.5V11.7469C21.5625 13.5375 20.1 15.0375 18.3094 15C17.4754 14.9828 16.6814 14.6395 16.0978 14.0435C15.5141 13.4476 15.1873 12.6466 15.1875 11.8125"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M20.25 14.3721V19.5002C20.25 19.6991 20.171 19.8899 20.0303 20.0305C19.8897 20.1712 19.6989 20.2502 19.5 20.2502H4.5C4.30109 20.2502 4.11032 20.1712 3.96967 20.0305C3.82902 19.8899 3.75 19.6991 3.75 19.5002V14.3721"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>

                                                </div>
                                                <h3 class="sub-title text-uppercase">{{ __('date_of_birth') }}</h3>
                                                <h2 class="title" id="candidate_birth_date">
                                                    {{ candidate.birth_date ? candidate.birth_date:'N/A' }}</h2>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="icon-box">
                                                <div class="icon-img">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9 17.25L3 18.75V5.25L9 3.75"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M15 20.25L9 17.25V3.75L15 6.75V20.25Z"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M15 6.75L21 5.25V18.75L15 20.25"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>

                                                </div>
                                                <h3 class="sub-title">{{ __('nationality') }}</h3>
                                                <h2 class="title" id="candidate_nationality">
                                                    {{ data.contact_info && data.contact_info.country ? data.contact_info.country
                        .name:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="icon-box">
                                                <div class="icon-img">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9 14.25H15" stroke="var(--primary-500)"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path d="M9 11.25H15" stroke="var(--primary-500)"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M15.0002 3.75H18.75C18.9489 3.75 19.1397 3.82902 19.2803 3.96967C19.421 4.11032 19.5 4.30109 19.5 4.5V20.25C19.5 20.4489 19.421 20.6397 19.2803 20.7803C19.1397 20.921 18.9489 21 18.75 21H5.25C5.05109 21 4.86032 20.921 4.71967 20.7803C4.57902 20.6397 4.5 20.4489 4.5 20.25V4.5C4.5 4.30109 4.57902 4.11032 4.71967 3.96967C4.86032 3.82902 5.05109 3.75 5.25 3.75H8.9998"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M8.25 6.75V6C8.25 5.00544 8.64509 4.05161 9.34835 3.34835C10.0516 2.64509 11.0054 2.25 12 2.25C12.9946 2.25 13.9484 2.64509 14.6517 3.34835C15.3549 4.05161 15.75 5.00544 15.75 6V6.75H8.25Z"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>

                                                </div>
                                                <h3 class="sub-title">{{ __('marital_status') }}</h3>
                                                <h2 class="title tw-capitalize">
                                                    {{ candidate.marital_status ? candidate.marital_status:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="icon-box">
                                                <div class="icon-img">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-miterlimit="10" />
                                                        <path
                                                            d="M12 15C14.0711 15 15.75 13.3211 15.75 11.25C15.75 9.17893 14.0711 7.5 12 7.5C9.92893 7.5 8.25 9.17893 8.25 11.25C8.25 13.3211 9.92893 15 12 15Z"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-miterlimit="10" />
                                                        <path
                                                            d="M5.98145 18.6913C6.54639 17.5806 7.40768 16.6478 8.46997 15.9963C9.53226 15.3448 10.7541 15 12.0003 15C13.2464 15 14.4683 15.3448 15.5306 15.9963C16.5929 16.6478 17.4542 17.5806 18.0191 18.6913"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>

                                                </div>
                                                <h3 class="sub-title">{{ __('gender') }}</h3>
                                                <h2 class="title">
                                                    {{ candidate.gender ? candidate.gender:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="icon-box">
                                                <div class="icon-img">
                                                    <i class="ph-suitcase-simple f-size-24 text-primary-500"></i>
                                                </div>
                                                <h3 class="sub-title">{{ __('experience') }}</h3>
                                                <h2 class="title" id="candidate_experience">
                                                    {{ candidate.experience ? candidate.experience.name:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="icon-box">
                                                <div class="icon-img">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0.75 9L12 3L23.25 9L12 15L0.75 9Z"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M17.625 22.5V12L12 9" stroke="var(--primary-500)"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M20.625 10.4004V15.5117C20.6253 15.6735 20.573 15.831 20.476 15.9605C19.8444 16.8009 17.18 19.8754 12 19.8754C6.82004 19.8754 4.15558 16.8009 3.52402 15.9605C3.42699 15.831 3.37469 15.6735 3.375 15.5117V10.4004"
                                                            stroke="var(--primary-500)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>

                                                </div>
                                                <h3 class="sub-title">{{ __('education') }}</h3>
                                                <h2 class="title">
                                                    {{ candidate.education ? candidate.education.name:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-widget">
                                    <div class="contact">
                                        <h2 class="title">{{ __('contact_information') }}</h2>
                                        <div class="contact-icon-box">
                                            <div class="icon-img">
                                                <svg width="24" height="24" viewBox="0 0 32 32" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16 28C22.6274 28 28 22.6274 28 16C28 9.37258 22.6274 4 16 4C9.37258 4 4 9.37258 4 16C4 22.6274 9.37258 28 16 28Z"
                                                        fill="none" stroke="var(--primary-500)" stroke-width="2"
                                                        stroke-miterlimit="10" />
                                                    <path d="M4 16H28" stroke="var(--primary-500)" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M16 27.678C18.7614 27.678 21 22.4496 21 16.0001C21 9.55062 18.7614 4.32227 16 4.32227C13.2386 4.32227 11 9.55062 11 16.0001C11 22.4496 13.2386 27.678 16 27.678Z"
                                                        stroke="var(--primary-500)" stroke-width="2"
                                                        stroke-miterlimit="10" />
                                                </svg>

                                            </div>
                                            <div class="info">
                                                <h3 class="subtitle">{{ __('website') }}</h3>
                                                <h2 class="title">
                                                    {{ candidate.website ? candidate.website:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="devider"></div>
                                        <div class="contact-icon-box">
                                            <div class="icon-img">
                                                <svg width="24" height="24" viewBox="0 0 32 32" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.2"
                                                        d="M16 3C13.3478 3.00001 10.8043 4.05358 8.92894 5.92894C7.05358 7.8043 6.00001 10.3478 6 13C6 22 16 29 16 29C16 29 26 22 26 13C26 10.3478 24.9464 7.8043 23.0711 5.92894C21.1957 4.05358 18.6522 3.00001 16 3ZM16 17C15.2089 17 14.4355 16.7654 13.7777 16.3259C13.1199 15.8864 12.6072 15.2616 12.3045 14.5307C12.0017 13.7998 11.9225 12.9956 12.0769 12.2196C12.2312 11.4437 12.6122 10.731 13.1716 10.1716C13.731 9.61216 14.4437 9.2312 15.2196 9.07686C15.9956 8.92252 16.7998 9.00173 17.5307 9.30448C18.2616 9.60723 18.8864 10.1199 19.3259 10.7777C19.7654 11.4355 20 12.2089 20 13C20 14.0609 19.5786 15.0783 18.8284 15.8284C18.0783 16.5786 17.0609 17 16 17V17Z"
                                                        fill="var(--primary-500)" />
                                                    <path d="M7 29H25" stroke="var(--primary-500)" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M16 17C18.2091 17 20 15.2091 20 13C20 10.7909 18.2091 9 16 9C13.7909 9 12 10.7909 12 13C12 15.2091 13.7909 17 16 17Z"
                                                        stroke="var(--primary-500)" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M26 13C26 22 16 29 16 29C16 29 6 22 6 13C6 10.3478 7.05357 7.8043 8.92893 5.92893C10.8043 4.05357 13.3478 3 16 3C18.6522 3 21.1957 4.05357 23.0711 5.92893C24.9464 7.8043 26 10.3478 26 13V13Z"
                                                        stroke="var(--primary-500)" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>

                                            </div>
                                            <div class="info">
                                                <h3 class="subtitle">{{ __('location') }}</h3>
                                                <h2 class="title" id="candidate_location">
                                                    {{ data.contact_info && data.contact_info.country ? data.contact_info.country.name:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="devider"></div>
                                        <div class="contact-icon-box">
                                            <div class="icon-img">
                                                <svg width="24" height="24" viewBox="0 0 32 32" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.2"
                                                        d="M11.5595 15.6025C12.5968 17.7232 14.3158 19.4344 16.4412 20.462C16.5967 20.5357 16.7687 20.5676 16.9403 20.5546C17.1119 20.5417 17.2771 20.4842 17.4198 20.388L20.5492 18.3012C20.6877 18.2089 20.8469 18.1526 21.0126 18.1374C21.1782 18.1221 21.3451 18.1485 21.498 18.214L27.3526 20.7231C27.5515 20.8076 27.7175 20.9545 27.8257 21.1415C27.9339 21.3286 27.9783 21.5457 27.9524 21.7602C27.7673 23.2082 27.0608 24.5392 25.9652 25.5038C24.8695 26.4684 23.4598 27.0005 22 27.0006C17.4913 27.0006 13.1673 25.2095 9.97919 22.0214C6.79107 18.8333 5 14.5093 5 10.0006C5.00008 8.54083 5.53224 7.13113 6.49685 6.03546C7.46146 4.93979 8.79237 4.23328 10.2404 4.04824C10.4549 4.02228 10.672 4.06673 10.8591 4.17491C11.0461 4.28309 11.193 4.44913 11.2775 4.64801L13.7888 10.5077C13.8537 10.6593 13.8802 10.8246 13.8658 10.9889C13.8514 11.1531 13.7967 11.3113 13.7064 11.4493L11.6268 14.6267C11.5322 14.7697 11.4762 14.9347 11.4644 15.1058C11.4526 15.2768 11.4854 15.4479 11.5595 15.6025Z"
                                                        fill="var(--primary-500)" />
                                                    <path
                                                        d="M11.5595 15.6025C12.5968 17.7232 14.3158 19.4344 16.4412 20.462C16.5967 20.5357 16.7687 20.5676 16.9403 20.5546C17.1119 20.5417 17.2771 20.4842 17.4198 20.388L20.5492 18.3012C20.6877 18.2089 20.8469 18.1526 21.0126 18.1374C21.1782 18.1221 21.3451 18.1485 21.498 18.214L27.3526 20.7231C27.5515 20.8076 27.7175 20.9545 27.8257 21.1415C27.9339 21.3286 27.9783 21.5457 27.9524 21.7602C27.7673 23.2082 27.0608 24.5391 25.9652 25.5038C24.8695 26.4684 23.4598 27.0005 22 27.0006C17.4913 27.0006 13.1673 25.2095 9.97919 22.0214C6.79107 18.8333 5 14.5093 5 10.0006C5.00008 8.54083 5.53224 7.13113 6.49685 6.03546C7.46146 4.93979 8.79237 4.23328 10.2404 4.04824C10.4549 4.02228 10.672 4.06673 10.8591 4.17491C11.0461 4.28309 11.193 4.44913 11.2775 4.64801L13.7888 10.5077C13.8537 10.6593 13.8802 10.8246 13.8658 10.9889C13.8514 11.1531 13.7967 11.3113 13.7064 11.4493L11.6268 14.6267C11.5322 14.7697 11.4762 14.9347 11.4644 15.1058C11.4526 15.2768 11.4854 15.4479 11.5595 15.6025V15.6025Z"
                                                        stroke="var(--primary-500)" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M19.9268 5C21.622 5.45592 23.1677 6.34928 24.409 7.59059C25.6503 8.8319 26.5437 10.3776 26.9996 12.0728"
                                                        stroke="var(--primary-500)" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M18.8916 8.86523C19.9087 9.13879 20.8362 9.6748 21.5809 10.4196C22.3257 11.1644 22.8618 12.0918 23.1353 13.1089"
                                                        stroke="var(--primary-500)" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>

                                            </div>
                                            <div class="info">
                                                <h3 class="subtitle">{{ __('phone') }}</h3>
                                                <h2 class="title" id="candidate_phone">
                                                    {{ data.contact_info && data.contact_info.phone ? data.contact_info.phone:'N/A' }}
                                                </h2>
                                                <h3 class="subtitle">{{ __('secondary_phone') }}</h3>
                                                <h2 class="title" id="candidate_seconday_phone">
                                                    {{ data.contact_info && data.contact_info.secondary_phone ? data.contact_info.secondary_phone:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="devider"></div>
                                        <div class="contact-icon-box">
                                            <div class="icon-img">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M21 5.25L12 13.5L3 5.25" stroke="var(--primary-500)"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M3 5.25H21V18C21 18.1989 20.921 18.3897 20.7803 18.5303C20.6397 18.671 20.4489 18.75 20.25 18.75H3.75C3.55109 18.75 3.36032 18.671 3.21967 18.5303C3.07902 18.3897 3 18.1989 3 18V5.25Z"
                                                        stroke="var(--primary-500)" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10.3628 12L3.23047 18.538" stroke="var(--primary-500)"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M20.7692 18.5381L13.6367 12" stroke="var(--primary-500)"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="info">
                                                <h3 class="subtitle">{{ __('email_address') }}</h3>
                                                <h2 class="title" id="contact_info_email">
                                                    {{ data.contact_info && data.contact_info.email ? data.contact_info.email:'N/A' }}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" @click="$emit('close-modal')"></button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                required: true
            },
            response: {
                type: Object,
                required: true
            },
            language: {
                type: Object,
                required: false
            }
        },
        data() {
            return {
                show: false,
                data: {},
                social: {},
                candidate: {},

                languageTranslation: {},
            }
        },
        methods:{
            __(key){
                if (this.languageTranslation) {
                    return this.languageTranslation[key] || key;
                }

                return key;
            }
        },
        watch: {
            response() {
                this.data = this.response.data
                this.social = this.response.data.social_info
                this.candidate = this.response.data.candidate
            }
        },
        mounted() {
            this.data = this.response.data
            this.social = this.response.data.social_info
            this.candidate = this.response.data.candidate
            this.languageTranslation = this.language
        }
    }
</script>
