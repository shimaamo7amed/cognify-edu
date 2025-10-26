<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Observation Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.6; color: #333; margin: 0; padding: 0 40px; }
        h2 { color: #2d3748; margin-bottom: 20px; text-align: center; font-size: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 120px; margin-bottom: 10px; }
        .section { margin-bottom: 25px; }
        .label { font-weight: bold; color: #1a202c; display: inline-block; width: 200px; vertical-align: top; }
        .value { display: inline-block; }
        .heading { font-size: 14px; font-weight: bold; color: #4a5568; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px; text-transform: uppercase; }
        p { margin: 4px 0; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('storage/images/logo.jpeg') }}" class="logo" alt="Logo">
        <h2>Observation Report</h2>
    </div>

    <div class="section">
        <div class="heading">Basic Information</div>
        <p><span class="label">Child Name:</span><span class="value">{{ $child->fullName }}</span></p>
        <p><span class="label">Observer:</span><span class="value">{{ $data['observer_name'] ?? 'N/A' }}</span></p>
        <p><span class="label">Observer Title:</span><span class="value">{{ $data['observer_title'] ?? 'N/A' }}</span></p>
        <p><span class="label">Observation Date:</span><span class="value">{{ $data['observation_date'] ?? 'N/A' }}</span></p>
        <p><span class="label">Session Duration:</span><span class="value">{{ $data['session_duration'] ?? 'N/A' }}</span></p>
        <p><span class="label">Location:</span><span class="value">{{ $data['observation_location'] ?? 'N/A' }}</span></p>
        <p><span class="label">Support Level:</span><span class="value">{{ isset($data['required_support_level']) ? ucfirst($data['required_support_level']) : 'N/A' }}</span></p>
    </div>

    <div class="section">
        <div class="heading">Observation</div>
        <p><span class="label">Child's Strengths:</span><span class="value">{{ $data['childs_strengths'] }}</span></p>
        <p><span class="label">Areas of Concern:</span><span class="value">{{ $data['areas_of_concern'] }}</span></p>
        <p><span class="label">Behavioral Observations:</span><span class="value">{{ $data['behavioral_observations'] }}</span></p>
        <p><span class="label">Academic Performance:</span><span class="value">{{ $data['academic_performance'] }}</span></p>
        <p><span class="label">Social Interactions:</span><span class="value">{{ $data['social_interactions'] }}</span></p>
    </div>

    <div class="section">
        <div class="heading">Assessments</div>
        <p><span class="label">Cognitive Assessment:</span><span class="value">{{ $data['cognitive_assessment'] }}</span></p>
        <p><span class="label">Emotional Assessment:</span><span class="value">{{ $data['emotional_assessment'] }}</span></p>
        <p><span class="label">Physical Assessment:</span><span class="value">{{ $data['physical_assessment'] }}</span></p>
        <p><span class="label">Communication Skills:</span><span class="value">{{ $data['communication_skills'] }}</span></p>
        <p><span class="label">Classroom Environment:</span><span class="value">{{ $data['classroom_environment'] }}</span></p>
        <p><span class="label">Teacher Interaction:</span><span class="value">{{ $data['teacher_interaction'] }}</span></p>
        <p><span class="label">Peer Interaction:</span><span class="value">{{ $data['peer_interaction'] }}</span></p>
    </div>

    <div class="section">
        <div class="heading">Goals & Strategies</div>
        <p><span class="label">Professional Recommendations:</span><span class="value">{{ $data['professional_recommendations'] }}</span></p>
        <p><span class="label">Short Term Goals:</span><span class="value">{{ $data['short_term_goals'] }}</span></p>
        <p><span class="label">Long Term Goals:</span><span class="value">{{ $data['long_term_goals'] }}</span></p>
        <p><span class="label">Intervention Strategies:</span><span class="value">{{ $data['intervention_strategies'] }}</span></p>
        <p><span class="label">Classroom Accommodations:</span><span class="value">{{ $data['classroom_accommodations'] }}</span></p>
    </div>

    <div class="section">
        <div class="heading">Follow Up</div>
        <p><span class="label">Next Steps:</span><span class="value">{{ $data['next_steps'] }}</span></p>
        <p><span class="label">Recommended Follow-up Date:</span><span class="value">{{ $data['recommended_follow_up_date'] }}</span></p>
        <p><span class="label">Parent Meeting Required:</span><span class="value">{{ $data['parent_meeting_required'] }}</span></p>
    </div>

</body>
</html>
