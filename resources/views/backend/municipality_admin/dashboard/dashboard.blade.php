@extends('backend.layouts.master')


@section('content')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }


        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }


        th {
            background-color: #f2f2f2;
        }


        tr:hover {
            background-color: #f5f5f5;
        }


     
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
    </style>


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


    <div class="mt-4">
        <div class="d-flex justify-content-between mb-4">
            <div class="border-bottom border-primary">
                {{-- <h2>{{ $page_title }}</h2> --}}
            </div>
            {{-- @include('backend.school_admin.assign_class_teacher.partials.action') --}}
        </div>




        {{-- For the material Design in the dashboard --}}

        <div class="row mb-3">
            <div class="col-12 ">
                <button class="btn btn-primary" onclick="$('#holidayRangeModal').modal('show')">
                    <i class="fa-solid fa-calendar-plus me-2"></i>
                    Mark Holiday
                </button>
            </div>
        </div>


        <div class="row">


            <div class="col-xl-4 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-school"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $totalSchools }}</div>
                            <div class="weight-500">Total Schools</div>
                        </div>
                    </div>
                </div>
            </div>


                <!-- Widget for Major Incidents -->
                <div class="col-xl-4 mb-50" onclick="$('#majorIncidentsModal').modal('show')" style="cursor: pointer;">
                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="circle-icon">
                                <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-bell-school"></i></div>
                            </div>
                            <div class="widget-data">
                                <div class="weight-800 font-18">{{ $major_incidents }}</div>
                                <div class="weight-500"> Major Incidents</div>
                            </div>
                        </div>
                    </div>
                </div>
   
        <!-- Modal for Major Incidents -->
        <div class="modal fade" id="majorIncidentsModal" tabindex="-1" aria-labelledby="majorIncidentsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="majorIncidentsModalLabel">Major Incidents Today</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($todays_major_incidents->isNotEmpty())
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>School Name</th>
                                        <th>Major Incidents</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todays_major_incidents as $incident)
                                        <tr>
                                            <td>{{ optional($incident->school)->name ?: 'Unknown School' }}</td>
                                            {{-- <td>{{ $incident->major_incidents }}</td> --}}
                                            <td style="max-width: 300px; word-wrap: break-word;">{{ $incident->major_incidents }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No major incidents reported today.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
   
        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Optional: You can initialize tooltips and other features here if needed.


            // Click handler for showing the modal
            $('.show-major-incidents-modal').click(function() {
                $('#majorIncidentsModal').modal('show');
            });
        });
    </script>


            <div class="col-xl-4 mb-50">
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


            <div class="col-xl-4 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-person-half-dress"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $totalGirls }}</div>
                            <div class="weight-500">Total Girls</div>
                        </div>
                       
                    </div>
                </div>
            </div>




            <div class="col-xl-4 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-person-half-dress"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $totalBoys }}</div>
                            <div class="weight-500">Total Boys</div>
                        </div>
                       
                    </div>
                </div>
            </div>




            <div class="col-xl-4 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-people-group"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $totalStaffs }}</div>
                            <div class="weight-500">Total Staffs</div>
                        </div>
                       
                    </div>
                </div>
            </div>


            <div class="col-xl-4 mb-50">
                <div class="bg-white box-shadow border-radius-10 height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-user"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $presentStaffs }}</div>
                            <div class="weight-500">Present Staffs</div>
                        </div>
                        <div class="progress-data">
                            <div id="chart3"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 mb-50">
                <div class="bg-white widget-style1 border-radius-10 height-100-p box-shadow">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-user-minus"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $absentStaffs }}</div>
                            <div class="weight-500">Absent Staffs</div>
                        </div>
                        <div class="progress-data">
                            <div id="chart2"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 mb-50">
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


            <div class="col-xl-4 mb-50">
                <div class="bg-white widget-style1 border-radius-10 height-100-p box-shadow">
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


            <div class="col-xl-4 mb-50">
                <div class="bg-white widget-style1 border-radius-10 height-100-p box-shadow">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-bell"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $noticeCount }}</div>
                            <div class="weight-500">Municipality Notices</div>
                        </div>
                    </div>
                </div>
            </div>      
           
            <div class="col-xl-4 mb-50">
                <div class="bg-white widget-style1 border-radius-10 height-100-p box-shadow">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="circle-icon">
                            <div class="icon border-radius-100 font-24 text-blue"><i class="fa-solid fa-trophy"></i></div>
                        </div>
                        <div class="widget-data">
                            <div class="weight-800 font-18">{{ $ECACount }}</div>
                            <div class="weight-500">Municipality ECA</div>
                        </div>
                    </div>
                </div>
            </div>      
         
            <div class="modal fade" id="holidayRangeModal" tabindex="-1" role="dialog" aria-labelledby="holidayRangeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="holidayRangeModalLabel">Mark Holiday</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Start Date Input -->
                            <div class="form-group">
                                <label for="holidayStartDate">Start Date:</label>
                                <input type="text" name="date" class="form-control" id="holidayStartDate" placeholder="YYYY-MM-DD" required>
                            </div>
                            <!-- End Date Input -->
                            <div class="form-group">
                                <label for="holidayEndDate">End Date:</label>
                                <input type="text" name="date" class="form-control" id="holidayEndDate" placeholder="YYYY-MM-DD">
                            </div>
                            <!-- Reason Input -->
                            <div class="form-group">
                                <label for="holidayReason">Reason:</label>
                                <input type="text" name="remarks" class="form-control" id="holidayReason" placeholder="e.g., Summer Vacation, Dashain Vacation" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="saveHolidayRange">Save Holiday</button>
                        </div>
                    </div>
                </div>
            </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Schools</th>
                    <th>Address</th>
                    <th>Total Students</th>
                    <th>Present Student</th>
                    <th>Absent Student</th>
                    <th>Total Staff</th>
                    <th>Present Staff</th>
                    <th>Absent Staff</th>
                    <th>Student Attendance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schoolData as $school)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $school['school_name'] }}</td>
                    <td>{{ $school['school_address'] }}</td>
                    <td>{{ $school['total_students'] }}</td>
                    <td>{{ $school['present_students'] }}</td>
                    <td>{{ $school['absent_students'] }}</td>
                    <td>{{ $school['total_staffs'] }}</td>
                    <td>{{ $school['present_staffs'] }}</td>
                    <td>{{ $school['absent_staffs'] }}</td>
                    <td>
                        @if (strtolower($school['attendance_status']) == 'full')
                            <span style="color: green;">{{ $school['attendance_status'] }}</span>
                        @elseif (strtolower($school['attendance_status']) == 'partial')
                            <span style="color: red;">{{ $school['attendance_status'] }}</span>
                        @else
                            {{ $school['attendance_status'] }}
                        @endif
                    </td>                    
                         
                </tr>
                @endforeach
            </tbody>
        </table>






        {{-- @foreach ($schoolData as $school)
        <div>
            <h3>{{ $school['school_name'] }}</h3>
            <p>Total Students: {{ $school['total_students'] }}</p>
            <p>Present Students: {{ $school['present_students'] }}</p>
            <p>Absent Students: {{ $school['absent_students'] }}</p>
            <p>Total Staffs: {{ $school['total_staffs'] }}</p>
            <p>Present Staffs: {{ $school['present_staffs'] }}</p>
            <p>Absent Staffs: {{ $school['absent_staffs'] }}</p>
        </div>
    @endforeach --}}




        {{-- <div class="card mb-4">
            <div class="card-body">
                <form id="filterForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="datetimepicker">Date:</label>
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                    <input id="nepali-datepicker" name="date" type="text"
                                        class="form-control datetimepicker-input" />
                                </div>
                                @error('date')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                    </div>
            </div>


            <div class="row">
                <div class="col-md-12 mt-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                </div>
            </div>


            <div class="card-body">
                <div class="school-wise-report">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Schools</th>
                                <th>Total Students</th>
                                <th>Present Student</th>
                                <th>Absent Student</th>
                                <th>Late Student</th>
                                <th>Total Staff</th>
                                <th>Present Staff</th>
                                <th>Absent Staff</th>
                                <th>Late Staff</th>
                                <th>Holiday Staff</th>
                                <th>Major Incidents</th>
                                <th>ECA/CCA</th>
                                <th>Miscellaneous</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schools_wise_reports as $report)
                                <tr>
                                    <td>{{ $report['school_name'] }}</td>
                                    <td>{{ $report['total_student'] }}</td>
                                    <td>{{ $report['present_student'] }}</td>
                                    <td>{{ $report['absent_student'] }}</td>
                                    <td>{{ $report['late_student'] }}</td>
                                    <td>{{ $report['total_staffs'] }}</td>
                                    <td>{{ $report['present_staffs'] }}</td>
                                    <td>{{ $report['absent_staffs'] }}</td>
                                    <td>{{ $report['late_staffs'] }}</td>
                                    <td>{{ $report['holiday_staffs'] }}</td>
                                    <td>{{ $report['major_incidents'] }}</td>
                                    <td>{{ $report['eca_cca'] }}</td>
                                    <td>{{ $report['miscellaneous'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        </div> --}}
        <div class="card mt-2">
            <div class="card-body">
                <div class="row p-4 justify-content-around" height="300">
                    <div class="col-md-5 col-lg-5 container-fluid">
                        <div class="bg-gray pt-5">
                            <canvas id="schoolWiseStudentChart" height="100%"></canvas>
                        </div>
                        <span class="fw-bold">School Wise Students</span>
                    </div>
                    <div class="col-md-5 col-lg-5 container-fluid">
                        <div class="bg-gray pt-5">
                            <canvas id="schoolWiseStaffsChart" height="100%">
                            </canvas>
                        </div>
                        <span class="fw-bold">School Wise Staffs</span>
                    </div>
                    <div class="col-md-12 col-lg-12 mt-4 ">
                        <div class="bg-gray pt-5">
                            <canvas id="schoolAttendanceChart" width="600" height="200"></canvas>
                        </div>
                        <span class="fw-bold">School Wise Student's Attendence</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
    @include('backend.includes.nepalidate')
    @include('backend.includes.chartjs')
    <script>
        // Attach click event handler to the search button
        $('#searchButton').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Get the selected class and section IDs
            var date = $('input[name="date"]').val();
            // Fetch schoolwise reports
            $.ajax({
                url: '{{ route('admin.school-wise-reports') }}',
                type: 'POST',
                data: {
                    date: date
                },
                success: function(data) {
                    // Clear existing table rows
                    $('.school-wise-report tbody').empty();
                    // Iterate over the fetched data and append rows to the table
                    $.each(data, function(key, value) {
                        var rowHtml = '<tr>';
                        rowHtml += '<td>' + value.school_name + '</td>';
                        rowHtml += '<td>' + value.total_student + '</td>';
                        rowHtml += '<td>' + value.present_student + '</td>';
                        rowHtml += '<td>' + value.absent_student + '</td>';
                        rowHtml += '<td>' + value.total_staffs + '</td>';
                        rowHtml += '<td>' + value.present_staffs + '</td>';
                        rowHtml += '<td>' + value.absent_staffs + '</td>';
                        rowHtml += '<td>' + value.major_incidents + '</td>';
                        rowHtml += '<td>' + value.eca_cca + '</td>';
                        rowHtml += '<td>' + value.miscellaneous + '</td>';
                        rowHtml += '</tr>';
                        $('.school-wise-report tbody').append(rowHtml);
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error('Ajax Request Error:', textStatus, errorThrown);
                }
            });
        });
        //number of student accordance to school
        var school_student_count = @json($school_students_count);
        var school_staffs_count = @json($school_staffs_count);
        var school_wise_student_attendences = @json($school_wise_student_attendences);
        //school-wise student
        const ctx = document.getElementById('schoolWiseStudentChart');
        new Chart(ctx, {
            type: 'bar',
            data: school_student_count,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        //school-wise staffs
        const schoolstaffcount = document.getElementById('schoolWiseStaffsChart');
        new Chart(schoolstaffcount, {
            type: 'bar',
            data: school_staffs_count,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Extract school names and attendance data
            var schoolNames = school_wise_student_attendences.map(function(item) {
                return item.school_name;
            });
            var presentStudents = school_wise_student_attendences.map(function(item) {
                return item.present_student;
            });
            var absentStudents = school_wise_student_attendences.map(function(item) {
                return item.absent_student;
            });
            // Create a bar chart
            var ctx = document.getElementById('schoolAttendanceChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: schoolNames,
                    datasets: [{
                        label: 'Present Students',
                        data: presentStudents,
                        backgroundColor: 'rgba(50, 200, 50, 0.5)', // Blue color for present students
                        borderWidth: 1
                    }, {
                        label: 'Absent Students',
                        data: absentStudents,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)', // Red color for absent students
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
        function updateAttendanceStatus() {
            $('.attendance-status').each(function() {
                var totalStudents = parseInt($(this).data('total-students'));
                var presentStudents = parseInt($(this).data('present-students'));
                var absentStudents = parseInt($(this).data('absent-students'));
                if (presentStudents + absentStudents < totalStudents) {
                    $(this).removeClass('btn-success').addClass('btn-warning').text('Partial');
                } else {
                    $(this).removeClass('btn-warning').addClass('btn-success').text('Full');
                }
            });
        }
        // Call the function when the page loads
        $(document).ready(function() {
            updateAttendanceStatus();
        });
    // Event listener for save button click
    $('#saveHolidayButton').click(function(e) {
      e.preventDefault();
      // Send AJAX request to mark all schools as holiday
      $.ajax({
        url: '', // Define this route in your web.php
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}', // Include CSRF token for Laravel
          // Include other data if needed (e.g., holiday date, etc.)
        },
        success: function(response) {
          // Close the modal
          $('#holidayModal').modal('hide');
          // Show a success message or update the UI to reflect changes
          alert('All schools have been marked as holiday successfully!');
          // Optionally, you can refresh the table or list of schools here to reflect changes.
        },
        error: function(xhr, status, error) {
          // Handle any errors
          console.log(xhr.responseText);
          alert('Something went wrong. Please try again.');
        }
      });
    });
    // Initialize date pickers for the holiday range modal
    $('#holidayStartDate, #holidayEndDate').nepaliDatePicker()
    $("#holidayStartDate").nepaliDatePicker({
    container: "#holidayRangeModal",
    dateFormat: "YYYY-MM-DD",
    ndpYear: true,
    ndpMonth: true,
    ndpYearCount: 200,
    onChange: function() {
        $(this).change();
    }
});
$("#holidayEndDate").nepaliDatePicker({
    container: "#holidayRangeModal",
    dateFormat: "YYYY-MM-DD",
    ndpYear: true,
    ndpMonth: true,
    ndpYearCount: 200,
    onChange: function() {
        $(this).change();
    }
});
// Open the holiday range modal
$('#markHolidayRangeButton').click(function() {
    $('#holidayRangeModal').modal('show');
});
// Handle saving the holiday range
$('#saveHolidayRange').click(function() {
    var startDate = $('#holidayStartDate').val();
    var endDate = $('#holidayEndDate').val();
    var reason = $('#holidayReason').val();
    if (!startDate || !endDate) {
        toastr.warning('Please select both start and end dates.');
        return;
    }
    if (confirm('Are you sure you want to mark holidays from ' + startDate + ' to ' + endDate + '?')) {
        $.ajax({
            url: '{{ route("admin.student.mark-holiday-range") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                start_date: startDate,
                end_date: endDate,
                reason: reason
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#holidayRangeModal').modal('hide');
                    // Optionally, update UI or refresh data
                } else {
                    toastr.error(response.message || 'Error marking holiday range.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error details:', xhr.responseText);
                toastr.error('Error marking holiday range. Please check the console for details.');
            }
        });
    }
});
    </script>
@endsection
@endsection