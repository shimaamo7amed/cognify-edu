<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Employee Request</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #007bff; text-align: center;">New Employee Request</h2>
    </div>

    <div class="content">
        <p>Dear Sir,</p>
        <p>We would like to inform you that a new Employee request has been received. Here are the request details:</p>

        <div class="section">
            <h3>Personal Information:</h3>
            <p><span class="label">First Name:</span> {{ $employee->first_name }}</p>
            <p><span class="label">Middle Name:</span> {{ $employee->middle_name }}</p>
            <p><span class="label">Last Name:</span> {{ $employee->last_name }}</p>
            <p><span class="label">Date of Birth:</span> {{ $employee->date_of_birth }}</p>
            <p><span class="label">Gender:</span> {{ $employee->gender }}</p>
            <p><span class="label">Nationality:</span> {{ $employee->nationality }}</p>
        </div>

        <div class="section">
            <h3>Contact Information:</h3>
            <p><span class="label">Phone:</span> {{ $employee->phone }}</p>
            <p><span class="label">Email:</span> {{ $employee->email }}</p>
            <p><span class="label">Street Address:</span> {{ $employee->street_address }}</p>
        </div>

        <div class="section">
            <h3>Educational Background:</h3>
            <p><span class="label">Highest Degree:</span> {{ $employee->highest_degree }}</p>
            <p><span class="label">Institution:</span> {{ $employee->institution }}</p>
            <p><span class="label">Graduation Year:</span> {{ $employee->graduation_year }}</p>
        </div>

        <div class="section">
            <h3>Other Certifications:</h3>
            <p><span class="label">Other Institution:</span> {{ $employee->other_institution }}</p>
            <p><span class="label">Other Degree Title:</span> {{ $employee->other_degree_title }}</p>
            <p><span class="label">Other Degree Institution:</span> {{ $employee->other_degree_institution }}</p>
            <p><span class="label">Other Degree Year:</span> {{ $employee->other_degree_year }}</p>
        </div>

        <div class="section">
            <h3>Professional Experience:</h3>
            <h4>Current Position:</h4>
            <p><span class="label">Title:</span> {{ $employee->current_position_title }}</p>
            <p><span class="label">Institution:</span> {{ $employee->current_institution }}</p>
            <p><span class="label">From:</span> {{ $employee->current_from }}</p>
            <p><span class="label">To:</span> {{ $employee->current_to }}</p>
            <p><span class="label">Responsibilities:</span> {{ $employee->current_responsibilities }}</p>

            <h4>Previous Position:</h4>
            <p><span class="label">Title:</span> {{ $employee->previous_position_title }}</p>
            <p><span class="label">Institution:</span> {{ $employee->previous_institution }}</p>
            <p><span class="label">From:</span> {{ $employee->previous_from }}</p>
            <p><span class="label">To:</span> {{ $employee->previous_to }}</p>
            <p><span class="label">Responsibilities:</span> {{ $employee->previous_responsibilities }}</p>
        </div>

        <div class="section">
            <h3>Skills & Competencies:</h3>
            <p><span class="label">Total Teaching Experience:</span> {{ $employee->total_teaching_experience }}</p>
            <p><span class="label">Special Needs Experience:</span> {{ $employee->special_needs_experience }}</p>
            <p><span class="label">Shadow Teacher Experience:</span> {{ $employee->shadow_teacher_experience }}</p>
            <p><span class="label">Computer Skills:</span> {{ $employee->computer_skills }}</p>
        </div>

        <div class="section">
            <h3>Language Proficiency:</h3>
            <p><span class="label">Arabic:</span> {{ $employee->arabic_proficiency }}</p>
            <p><span class="label">English:</span> {{ $employee->english_proficiency }}</p>
            <p><span class="label">French:</span> {{ $employee->french_proficiency }}</p>
            <p><span class="label">Italian:</span> {{ $employee->italian_proficiency }}</p>
        </div>

        <div class="section">
            <h3>Training & Development:</h3>
            <p><span class="label">Training Title:</span> {{ $employee->training_title }}</p>
            <p><span class="label">Training Date:</span> {{ $employee->training_date }}</p>
            <p><span class="label">Professional Memberships:</span> {{ $employee->professional_memberships }}</p>

        </div>

        <div class="section">
            <h3>Motivation & Suitability:</h3>
            <p><span class="label">Motivation:</span> {{ $employee->motivation }}</p>
            <p><span class="label">Suitability:</span> {{ $employee->suitability }}</p>
        </div>

        <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            <p>Please review and process this employee request through the admin dashboard.</p>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated email - please do not reply</p>
    </div>
</body>
</html>
