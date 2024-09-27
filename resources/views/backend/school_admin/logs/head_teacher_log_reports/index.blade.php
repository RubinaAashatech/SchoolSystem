@extends('backend.layouts.master')

@section('content')
    <div class="mt-4">
        <div class="d-flex justify-content-between mb-4">
            <div class="border-bottom border-primary">
                <h2>Head Teacher Log Reports</h2>
            </div>
        </div>
        <div class="mt-4">
            <div class="card">
                <div class="card-body">
                    <h5>Select Date</h5>
                    <div class="mt-4 d-flex flex-column">
                        <div class="p-2 label-input">
                            <label for="nepali-datepicker">Date:</label>
                            <div class="form-group">
                                <div class="input-group date" id="admission-datetimepicker" data-target-input="nearest">
                                    <input id="nepali-datepicker" name="date" type="text" class="form-control datetimepicker-input" />
                                </div>
                                @error('date')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="search-button-container d-flex col-md-3 justify-content-end mt-2">
                            <button id="searchButton" class="btn btn-sm btn-primary">Search</button>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <p id="messagePlaceholder"></p>
                    <div class="hr-line-dashed"></div>
                    <div class="table-responsive">
                        <table id="report-table" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Present Boys</th>
                                    <th>Present Girls</th>
                                    <th>Absent Boys</th>
                                    <th>Absent Girls</th>
                                    <th>Total Boys</th>
                                    <th>Total Girls</th>
                                </tr>
                            </thead>
                            <tbody id="report-table-body">
                                <!-- Table rows will be dynamically generated here -->
                            </tbody>
                        </table>
                        <table id="summary-table" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th>Description</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Students: </td>
                                    <td id="totalStudents">{{ $totalStudents }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Present Boys:</td>
                                    <td id="totalPresentBoys">{{ $totalPresentBoys }}</td>
                                </tr>
                                <tr>
                                    <td>Present Girls:</td>
                                    <td id="totalPresentGirls">{{ $totalPresentGirls }}</td>
                                </tr>
                                <tr>
                                    <td>Absent Boys:</td>
                                    <td id="totalAbsentBoys">{{ $totalAbsentBoys }}</td>
                                </tr>
                                <tr>
                                    <td>Absent Girls:</td>
                                    <td id="totalAbsentGirls">{{ $totalAbsentGirls }}</td>
                                </tr>
                                <tr>
                                    <td>Present Staffs: </td>
                                    <td id="presentStaffs">{{ $presentStaffs }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Absent Staffs: </td>
                                    <td id="absentStaffs">{{ $absentStaffs }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Major Incident: </td>
                                    <td id="majorIncident">{{ $majorIncident }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Major Work Observation: </td>
                                    <td id="majorWorkObservation">{{ $majorWorkObservation }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Assembly Management: </td>
                                    <td id="assemblyManagement">{{ $assemblyManagement }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Miscellaneous: </td>
                                    <td id="miscellaneous">{{ $miscellaneous }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('backend.includes.nepalidate')
    <script>
        $(document).ready(function() {
            var currentDate = NepaliFunctions.GetCurrentBsDate();
            var formattedDate = currentDate.year + '-' + ('0' + currentDate.month).slice(-2) + '-' + ('0' + currentDate.day).slice(-2);

            $('#nepali-datepicker').val(formattedDate);

            $('#nepali-datepicker').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 10
            });

            $('#searchButton').click(function () {
                var selectedDate = $('#nepali-datepicker').val();

                $.ajax({
                    url: "{{ route('admin.headteacherlog-reports.index') }}",
                    method: 'GET',
                    data: { date: selectedDate },
                    success: function(response) {
                        console.log(response); // Check response in console for debugging
                        // Update table and summary with response data
                        var tableBody = $('#report-table-body');
                        tableBody.empty();

                        var previousClass = null;
                        var rowspanCount = 0;
                        var classDataMap = {};

                        $.each(response.classWiseCounts, function(index, data) {
                            if (!classDataMap[data.class_name]) {
                                classDataMap[data.class_name] = [];
                            }
                            classDataMap[data.class_name].push(data);
                        });

                        $.each(classDataMap, function(className, sections) {
                            rowspanCount = sections.length;
                            $.each(sections, function(index, data) {
                                var rowHtml = '<tr>';
                                if (index === 0) {
                                    rowHtml += '<td rowspan="' + rowspanCount + '">' + data.class_name + '</td>';
                                }
                                rowHtml += '<td>' + data.section_name + '</td>';
                                rowHtml += '<td>' + data.present_boys + '</td>';
                                rowHtml += '<td>' + data.present_girls + '</td>';
                                rowHtml += '<td>' + data.absent_boys + '</td>';
                                rowHtml += '<td>' + data.absent_girls + '</td>';
                                rowHtml += '<td>' + data.total_boys + '</td>';
                                rowHtml += '<td>' + data.total_girls + '</td>';
                                rowHtml += '</tr>';
                                tableBody.append(rowHtml);
                            });
                        });

                        $('#totalStudents').text(response.totalStudents);
                        $('#presentStaffs').text(response.presentStaffs);
                        $('#absentStaffs').text(response.absentStaffs);
                        $('#totalPresentBoys').text(response.totalPresentBoys);
                        $('#totalPresentGirls').text(response.totalPresentGirls);
                        $('#totalAbsentBoys').text(response.totalAbsentBoys);
                        $('#totalAbsentGirls').text(response.totalAbsentGirls);
                        $('#majorIncident').text(response.majorIncident);
                        $('#majorWorkObservation').text(response.majorWorkObservation);
                        $('#assemblyManagement').text(response.assemblyManagement);
                        $('#miscellaneous').text(response.miscellaneous);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log detailed error message
                        $('#messagePlaceholder').text('Error: ' + xhr.responseText); // Display error message
                    }
                });
            });
        });
    </script>
@endsection
