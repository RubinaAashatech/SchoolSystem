@extends('backend.layouts.master')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif


    <style>
        
        a {
            outline: none;
            text-decoration: none;
            color: #555;
        }

        a:hover,
        a:focus {
            outline: none;
            text-decoration: none;
        }

        img {
            border: 0;
        }

        input,
        textarea,
        select {
            outline: none;
            resize: none;
            font-family: 'Muli', sans-serif;
        }

        a,
        input,
        button {
            outline: none !important;
        }

        button::-moz-focus-inner {
            border: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
            font-weight: 700;
            color: #202342;
            font-family: 'Muli', sans-serif;
        }

        img {
            border: 0;
            vertical-align: top;
            max-width: 100%;
            height: auto;
        }

        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        p {
            margin: 0 0 15px 0;
            padding: 0;
        }

        .container-fluid {
            max-width: 1900px;
        }

        /* Common Class */
        .pd-5 {
            padding: 5px;
        }

        .pd-10 {
            padding: 10px;
        }

        .pd-20 {
            padding: 20px;
        }

        .pd-30 {
            padding: 30px;
        }

        .pb-10 {
            padding-bottom: 10px;
        }

        .pb-20 {
            padding-bottom: 20px;
        }

        .pb-30 {
            padding-bottom: 30px;
        }

        .pt-10 {
            padding-top: 10px;
        }

        .pt-20 {
            padding-top: 20px;
        }

        .pt-30 {
            padding-top: 30px;
        }

        .pr-10 {
            padding-right: 10px;
        }

        .pr-20 {
            padding-right: 20px;
        }

        .pr-30 {
            padding-right: 30px;
        }

        .pl-10 {
            padding-left: 10px;
        }

        .pl-20 {
            padding-left: 20px;
        }

        .pl-30 {
            padding-left: 30px;
        }

        .px-30 {
            padding-left: 30px;
            padding-right: 30px;
        }

        .px-20 {
            padding-left: 20px;
            padding-right: 20px;
        }

        .py-30 {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .py-20 {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .mb-50 {
            margin-bottom: 50px;
        }

        .font-30 {
            font-size: 30px;
            line-height: 1.46em;
        }

        .font-24 {
            font-size: 24px;
            line-height: 1.5em;
        }

        .font-20 {
            font-size: 20px;
            line-height: 1.5em;
        }

        .font-18 {
            font-size: 18px;
            line-height: 1.6em;
        }

        .font-16 {
            font-size: 16px;
            line-height: 1.75em;
        }

        .font-14 {
            font-size: 14px;
            line-height: 1.85em;
        }

        .font-12 {
            font-size: 12px;
            line-height: 2em;
        }

        .weight-300 {
            font-weight: 300;
        }

        .weight-400 {
            font-weight: 400;
        }

        .weight-500 {
            font-weight: 500;
        }

        .weight-600 {
            font-weight: 600;
        }

        .weight-700 {
            font-weight: 700;
        }

        .weight-800 {
            font-weight: 800;
        }

        .text-blue {
            color: #07023d;
        }

        .text-dark {
            color: #000000;
        }

        .text-white {
            color: #ffffff;
        }

        .height-100-p {
            height: 100%;
        }

        .bg-white {
            background: #ffffff;
        }

        .border-radius-10 {
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
        }

        .border-radius-100 {
            -webkit-border-radius: 100%;
            -moz-border-radius: 100%;
            border-radius: 100%;
        }

        .box-shadow {
            -webkit-box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
            -moz-box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
            box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
        }

        .gradient-style1 {
            background-image: linear-gradient(135deg, #43CBFF 10%, #9708CC 100%);
        }

        .gradient-style2 {
            background-image: linear-gradient(135deg, #72EDF2 10%, #5151E5 100%);
        }

        .gradient-style3 {
            background-image: radial-gradient(circle 732px at 96.2% 89.9%, rgba(70, 66, 159, 1) 0%, rgba(187, 43, 107, 1) 92%);
        }

        .gradient-style4 {
            background-image: linear-gradient(135deg, #FF9D6C 10%, #BB4E75 100%);
        }

        /* widget style 1 */

        .widget-style1 {
            padding: 20px 10px;
        }

        .widget-style1 .circle-icon {
            width: 60px;
        }

        .widget-style1 .circle-icon .icon {
            width: 60px;
            height: 60px;
            background: #ecf0f4;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .widget-style1 .widget-data {
            width: calc(100% - 150px);
            padding: 0 15px;
        }

        .widget-style1 .progress-data {
            width: 90px;
        }

        .widget-style1 .progress-data .apexcharts-canvas {
            margin: 0 auto;
        }

        .widget-style2 .widget-data {
            padding: 20px;
        }

        .widget-style3 {
            padding: 30px 20px;
        }

        .widget-style3 .widget-data {
            width: calc(100% - 60px);
        }

        .widget-style3 .widget-icon {
            width: 60px;
            font-size: 45px;
            line-height: 1;
        }

        .apexcharts-legend-marker {
            margin-right: 6px !important;
        }

        .custom-modal .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .custom-modal .modal-header {
        border-bottom: none;
        padding: 20px 30px;
        background-color: #f8f9fa;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
    .custom-modal .modal-title {
        font-weight: 600;
        color: #333;
    }
    .custom-modal .modal-body {
        padding: 30px;
    }
    .custom-modal .modal-footer {
        border-top: none;
        padding: 20px 30px;
    }
    .custom-modal .close {
        font-size: 28px;
        color: #aaa;
        transition: color 0.2s ease;
    }
    .custom-modal .close:hover {
        color: #333;
    }
    .custom-modal .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }
    .custom-modal .btn-primary:hover {
        background-color: #0056b3;
    }
    .custom-modal .attachment-link {
        display: inline-block;
        margin-top: 15px;
        color: #007bff;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .custom-modal .attachment-link:hover {
        color: #0056b3;
    }
    </style>

    <div class="mt-4">
        {{-- <div class="d-flex justify-content-between mb-4">
            <div class="border-bottom border-primary">
                <h2>{{ $page_title }}</h2>
            </div>
        </div> --}}

        
        <div class="row">
            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-children"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $totalStudents }}</div>
                            <div class="weight-500">Total Students</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-child-dress"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $totalGirls }}</div>
                            <div class="weight-500">Total Girls</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-child"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $totalBoys }}</div>
                            <div class="weight-500">Total Boys</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-clipboard-user"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $presentStudents }}</div>
                            <div class="weight-500">Present Students</div>
                        </div>
                        <div class="progress-data">
                            <div id="chart3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-xmark"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $absentStudents }}</div>
                            <div class="weight-500">Absent Students</div>
                        </div>
                        <div class="progress-data">
                            <div id="chart2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-child-dress"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $presentGirls }}</div>
                            <div class="weight-500">Present Girls</div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-child"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $presentBoys }}</div>
                            <div class="weight-500">Present Boys</div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-xmark"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $absentGirls }}</div>
                            <div class="weight-500">Absent Girls</div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-xl-3 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-xmark"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $absentBoys }}</div>
                            <div class="weight-500">Absent Boys</div>
                        </div>
                    </div>
                </div>
            </div>  
            
            <div class="col-xl-3 mb-50">
                <a href="{{ route('admin.notices.index') }}" class="text-decoration-none">
                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="circle-icon">
                                <div class="icon border-radius-100 font-24 text-blue">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                            </div>
                            <div class="widget-data">
                                <div class="weight-800 font-18">{{ $noticeCount }}</div>
                                <div class="weight-500">Municipality Notice</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
        </div>
        </div>
        
            <div class="card">
                <div class="card-body">
                    <div class="row p-4 justify-content-around">
                        <div class="col-md-5 col-lg-5 container-fluid">
                            <div class="bg-gray pt-5">
                                <canvas id="classWiseStudentsChart" height="300"></canvas>
                            </div>
                            <span class="fw-bold">Class Wise Students</span>
                        </div>
                        <div class="col-md-5 col-lg-5 container-fluid">
                            <div class="bg-gray pt-5">
                                <canvas id="staffChart" height="300"></canvas>
                            </div>
                            <span class="fw-bold">Staff by Designation</span>
                        </div>
                        <div class="col-md-6 col-lg-6 mt-4">
                            <span class="fw-bold">Class Wise Student's Attendance</span>
                            <div class="bg-gray pt-5">
                                <canvas id="classWiseAttendanceChart" height="300"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 mt-4">
                            <span class="fw-bold">Staff's Attendance</span>
                            <div class="bg-gray pt-5">
                                <canvas id="staffAttendanceChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @if($unreadNotices->isNotEmpty())
                @foreach($unreadNotices as $notice)
                <div id="noticeModal-{{ $notice->id }}" class="modal custom-modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $notice->title }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>{{ $notice->description }}</p>
                                @if($notice->pdf_image)
                                <a href="{{ asset('storage/'.$notice->pdf_image) }}" target="_blank" class="attachment-link">
                                    <i class="fas fa-paperclip mr-2"></i>View Attachment
                                </a>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary markAsRead" data-notice-id="{{ $notice->id }}">Got it!</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            
                <script>
                $(document).ready(function() {
                    @foreach($unreadNotices as $notice)
                        $('#noticeModal-{{ $notice->id }}').modal('show');
                    @endforeach
            
                    $('.markAsRead').on('click', function() {
                        var noticeId = $(this).data('notice-id');
                        $.ajax({
                            url: '{{ route('admin.notice.markAsRead', '') }}/' + noticeId,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                $('#noticeModal-' + noticeId).modal('hide');
                            },
                            error: function(xhr, status, error) {
                                console.error("Error marking notice as read: ", error);
                                $('#noticeModal-' + noticeId).modal('hide');
                            }
                        });
                    });
            
                    $('.custom-modal .close').on('click', function() {
                        $(this).closest('.modal').modal('hide');
                    });
                });
                </script>
            @endif --}}
            </div>
        @endsection
        
        @section('scripts')
            @include('backend.includes.chartjs')
            <script>
                var class_wise_students = @json($class_wise_students);
                var class_wise_student_attendances = @json($class_wise_student_attendances);
                var staff_data = @json($staff_data);
                var staff_attendance = @json($staff_attendance);
            
                document.addEventListener('DOMContentLoaded', function() {
                    // Class-wise Student Chart
                                const ctx = document.getElementById('classWiseStudentsChart').getContext('2d');
                                new Chart(ctx, {
                                    type: 'bar', // or 'line' or any other chart type
                                    data: class_wise_students,
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
            
                    // Staff by Role Chart
                    const ctxStaff = document.getElementById('staffChart').getContext('2d');
                    new Chart(ctxStaff, {
                        type: 'bar',
                        data: staff_data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
            
                    // Class-wise Student Attendance Chart
                    const ctxAttendance = document.getElementById('classWiseAttendanceChart').getContext('2d');
                    new Chart(ctxAttendance, {
                        type: 'bar',
                        data: class_wise_student_attendances,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                                
                    // Staff Attendance Chart
                    const ctxStaffAttendance = document.getElementById('staffAttendanceChart').getContext('2d');
                    new Chart(ctxStaffAttendance, {
                        type: 'bar',
                        data: staff_attendance,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
            </script>
        @endsection
