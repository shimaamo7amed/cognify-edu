<x-filament::page>
    <div class="space-y-4">

        <div class="bg-white p-6 rounded-xl border shadow-sm">

            <h2 class="text-lg font-bold text-gray-800 mb-4">
                {{ ucfirst($type) }} Report Details
            </h2>

            <p class="text-sm text-gray-700 mb-4">
                <strong>Child:</strong> {{ $report->child->fullName ?? 'Unnamed' }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

                @if($type === 'observation')
                    <x-report-item label="Child's Strengths" :value="$report->childs_strengths" />
                    <x-report-item label="Areas of Concern" :value="$report->areas_of_concern" />
                    <x-report-item label="Behavioral Observations" :value="$report->behavioral_observations" />
                    <x-report-item label="Academic Performance" :value="$report->academic_performance" />
                    <x-report-item label="Social Interactions" :value="$report->social_interactions" />
                    <x-report-item label="Cognitive Assessment" :value="$report->cognitive_assessment" />
                    <x-report-item label="Emotional Assessment" :value="$report->emotional_assessment" />
                    <x-report-item label="Physical Assessment" :value="$report->physical_assessment" />
                    <x-report-item label="Communication Skills" :value="$report->communication_skills" />
                    <x-report-item label="Classroom Environment" :value="$report->classroom_environment" />
                    <x-report-item label="Teacher Interaction" :value="$report->teacher_interaction" />
                    <x-report-item label="Peer Interaction" :value="$report->peer_interaction" />
                    <x-report-item label="Recommendations" :value="$report->professional_recommendations" />
                    <x-report-item label="Short Term Goals" :value="$report->short_term_goals" />
                    <x-report-item label="Long Term Goals" :value="$report->long_term_goals" />
                    <x-report-item label="Intervention Strategies" :value="$report->intervention_strategies" />
                    <x-report-item label="Classroom Accommodations" :value="$report->classroom_accommodations" />
                    <x-report-item label="Next Steps" :value="$report->next_steps" />
                    <x-report-item label="Recommended Follow-up Date" :value="$report->recommended_follow_up_date?->format('Y-m-d')" />
                    <x-report-item label="Parent Meeting Required" :value="$report->parent_meeting_required ? 'Yes' : 'No'" />
                    <x-report-item label="Status" :value="$report->status" />
                    <x-report-item label="Progress" :value="$report->progress . '%'" />
                    <x-report-item label="Sent At" :value="$report->sent_at" />
                    <x-report-item label="Delivery Status" :value="$report->delivery_status" />

                @elseif($type === 'daily')
                    <x-report-item label="Report Date" :value="$report->report_date" />
                    <x-report-item label="Attention Activity" :value="$report->attention_activity" />
                    <x-report-item label="Attention Level" :value="$report->attention_level" />
                    <x-report-item label="Positive Behavior" :value="$report->positive_behavior" />
                    <x-report-item label="Communication" :value="$report->communication" />
                    <x-report-item label="Social Interaction" :value="$report->social_interaction" />
                    <x-report-item label="Meltdown" :value="$report->meltdown" />
                    <x-report-item label="General Behavior" :value="$report->general_behavior" />
                    <x-report-item label="Reinforcers" :value="$report->reinforcers" />
                    <x-report-item label="Academic" :value="$report->academic" />
                    <x-report-item label="Independence" :value="$report->independence" />
                    <x-report-item label="Overall Rating" :value="$report->overall_rating" />
                    <x-report-item label="Comments" :value="$report->comments" />
                    <x-report-item label="Employee Name" :value="$report->employee_name" />
                    <x-report-item label="Status" :value="$report->status" />
                @endif

            </div>

        </div>

    </div>
</x-filament::page>
