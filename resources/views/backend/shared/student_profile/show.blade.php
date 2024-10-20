@extends('backend.layouts.master')

@section('content')
    <div class="container my-5">
        <div class="profile-container">
            <div class="profile-header text-white p-4 rounded d-flex justify-content-between align-items-center no-print">
                <div class="profile-info">
                    <h2 class="mb-1">{{ $student->user->f_name }} {{ $student->user->m_name }} {{ $student->user->l_name }}</h2>
                    <p class="mb-0" style="color: black">{{ $student->user->email }}</p>
                    <strong><span class="mb-0" style="color: black">{{ $student->user->gender }}</span></strong>
                </div>
                <div class="button-group">
                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm same-size-btn"><i class="fa fa-angle-double-left"></i> Back</a>
                    <button onclick="window.print()" class="btn btn-success btn-sm same-size-btn"><i class="fa fa-print"></i> Print</button>
                </div>                
            </div>

            <div id="printable-content">
                <!-- Student Basic Information -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary">Student Basic Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Full Name:</strong>
                                <p>{{ $student->user->f_name }} {{ $student->user->m_name }} {{ $student->user->l_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Email:</strong>
                                <p>{{ $student->user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Gender:</strong>
                                <p>{{ $student->user->gender }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Date of Birth:</strong>
                                <p>{{ $student->user->dob }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Phone:</strong>
                                <p>{{ $student->user->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Blood Group:</strong>
                                <p>{{ $student->user->blood_group }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Religion:</strong>
                                <p>{{ $student->user->religion }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Father's Name:</strong>
                                <p>{{ $student->user->father_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Father's Occupation:</strong>
                                <p>{{ $student->user->father_occupation }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Mother's Name:</strong>
                                <p>{{ $student->user->mother_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Mother's Occupation:</strong>
                                <p>{{ $student->user->mother_occupation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Exam Results -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary">Exam Results</h4>
                        @if(empty($processedTerminalResults) && empty($processedFinalResults))
                            <p>No exam results available.</p>
                        @else
                            <div class="bg-light p-3 rounded mb-3">
                                <h5>For Class: {{ $class }} ({{ $section }})</h5>
                                @if(!empty($processedFinalResults))
                                    <div class="bg-light p-3 rounded mb-3">
                                        <h5>{{ $finalExamName }} CGPA: {{ $finalCGPA }}</h5>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Attendance Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary">Attendance</h4>
                        @if($attendanceData->isEmpty())
                            <p>No attendance data available.</p>
                        @else
                            @foreach($attendanceData as $year => $data)
                                <div class="bg-light p-3 rounded mb-2">
                                    <h5>Year: {{ $year }}</h5>
                                    <p>Present Days: {{ $data['present_days'] }}</p> 
                                    <p>Absent Days: {{ $data['absent_days'] }}</p>
                                    <p>Attendance Percentage: {{ $data['attendance_percentage'] }}%</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- ECA Participation -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary">Extra-Curricular Activities</h4>
                        @if($ecaParticipations->isEmpty())
                            <p>No ECA participation data available.</p>
                        @else
                            @foreach($ecaParticipations as $participation)
                                <div class="bg-light p-3 rounded mb-2">
                                    <h5>{{ $participation->ecaActivity->title ?? 'N/A' }}</h5>
                                    <p>Class: {{ $participation->class->class ?? 'N/A' }} / Section: {{ $participation->section->section_name ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Awards Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary">Awards</h4>
                        @if($awards->isNotEmpty())
                            @foreach($awards as $award)
                                <div class="bg-light p-3 rounded mb-2">
                                    <h5>{{ $award['result_type'] }}</h5>
                                    <p>{{ $award['description'] }}</p>
                                </div>
                            @endforeach
                        @else
                            <p>No awards won by this student.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .profile-container {
            max-width: 800px;
            margin: auto;
        }
        .profile-header {
            background-color: #007bff;
            color: #fff;
            border-radius: 15px;
        }
        .profile-info h2, .profile-info p {
            color: #fff;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.4rem;
            color: #007bff;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .btn-light {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .bg-light {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        

        @media print {
            body * {
                visibility: hidden;
            }
            #printable-content,
            #printable-content * {
                visibility: visible;
            }
            #printable-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print,
            .profile-header,
            .button-group {
                display: none !important;
            }
            .card {
                border: none;
                box-shadow: none;
            }
            .card-body {
                padding: 0;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
            html, body {
                width: 210mm;
                height: 297mm;
            }
            .container {
                width: 100% !important;
                max-width: none !important;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        window.onbeforeprint = function() {
            document.body.innerHTML = document.getElementById('printable-content').innerHTML;
        };
        window.onafterprint = function() {
            location.reload();
        };
    </script>
@endsection
