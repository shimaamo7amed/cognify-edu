<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Report</title>
    <style>body { font-family: DejaVu Sans; }</style>
</head>
<body>
    <h2 style="text-align:center;">Daily Report</h2>
    <p><strong>Child:</strong> {{ $report->child->fullName }}</p>
    <p><strong>Observer:</strong> {{ $report->employee->name.' '.$report->employee->middle_name.' '.$report->employee->last_name }}</p>
    <p><strong>Date:</strong> {{ $report->report_date }}</p>
    <hr>
    <p><strong>Attention Activity:</strong> {{ $report->attention_activity }}</p>
    <p><strong>Attention Level:</strong> {{ $report->attention_level }}</p>
    <p><strong>Positive Behavior:</strong> {{ $report->positive_behavior }}</p>
    <p><strong>Communication:</strong> {{ $report->communication }}</p>
    <p><strong>Social Interaction:</strong> {{ $report->social_interaction }}</p>
    <p><strong>Meltdown:</strong> {{ $report->meltdown }}</p>
    <p><strong>General Behavior:</strong> {{ $report->general_behavior }}</p>
    <p><strong>Reinforcers:</strong> {{ $report->reinforcers }}</p>
    <p><strong>Academic:</strong> {{ $report->academic }}</p>
    <p><strong>Independence:</strong> {{ $report->independence }}</p>
    <p><strong>Overall Rating:</strong> {{ $report->overall_rating }}</p>
    <p><strong>Comments:</strong> {{ $report->comments }}</p>
</body>
</html>
