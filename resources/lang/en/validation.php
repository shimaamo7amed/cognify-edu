<?php

return [

    'attributes' => [

        // 🟢 CognifyParentRequest
        'name'        => 'Name',
        'email'       => 'Email',
        'phone'       => 'Phone number',
        'password'    => 'Password',
        'address'     => 'Address',
        'governorate' => 'Governorate',
        'role'        => 'Role',

        // 🟢 CognifyChildRequest
        'childPhoto'       => 'Child photo',
        'fullName'         => 'Full name',
        'age'              => 'Age',
        'schoolName'       => 'School name',
        'schoolAddress'    => 'School address',
        'homeAddress'      => 'Home address',
        'gender'           => 'Gender',
        'textDescription'  => 'Text description',
        'voiceRecording'   => 'Voice recording',
        'foodAllergies'    => 'Food allergies',
        'environmentalAllergies' => 'Environmental allergies',
        'severityLevels'   => 'Severity levels',
        'medicationAllergies' => 'Medication allergies',
        'medicalConditions'=> 'Medical conditions',

        // 🟢 EmployeeRequest
        'first_name'  => 'First name',
        'middle_name' => 'Middle name',
        'last_name'   => 'Last name',
        'date_of_birth' => 'Date of birth',
        'gender' => 'Gender',
        'nationality' => 'Nationality',
        'street_address' => 'Street address',
        'highest_degree' => 'Highest degree',
        'institution'    => 'Institution',
        'graduation_year'=> 'Graduation year',
        'other_institution' => 'Other institution',
        'other_degree_title' => 'Other degree title',
        'other_degree_institution' => 'Other degree institution',
        'other_degree_year' => 'Other degree year',
        'current_position_title' => 'Current position title',
        'current_institution'    => 'Current institution',
        'current_from'           => 'Current job start date',
        'current_to'             => 'Current job end date',
        'current_responsibilities'=> 'Current responsibilities',
        'previous_position_title' => 'Previous position title',
        'previous_institution'    => 'Previous institution',
        'previous_from'           => 'Previous job start date',
        'previous_to'             => 'Previous job end date',
        'previous_responsibilities'=> 'Previous responsibilities',
        'total_teaching_experience' => 'Total teaching experience',
        'special_needs_experience'  => 'Special needs experience',
        'shadow_teacher_experience' => 'Shadow teacher experience',
        'arabic_proficiency'  => 'Arabic proficiency',
        'english_proficiency' => 'English proficiency',
        'french_proficiency'  => 'French proficiency',
        'italian_proficiency' => 'Italian proficiency',
        'computer_skills'     => 'Computer skills',
        'training_title'      => 'Training title',
        'training_date'       => 'Training date',
        'professional_memberships' => 'Professional memberships',
        'motivation'          => 'Motivation',
        'suitability'         => 'Suitability',

        // 🟢 ForgetPasswordRequest + ResetPasswordRequest
        'otp'   => 'Verification code',

        // 🟢 ObservationChildCaseRequest
        'child_id'   => 'Child ID',
        'session_id' => 'Session ID',
        'slot_date'  => 'Slot date',
        'slot_time'  => 'Slot time',

        // 🟢 ParentLoginRequest
        'password' => 'Password',

        // 🟢 PartnerRequest
        'organizationName'  => 'Organization name',
        'contactPersonName' => 'Contact person name',
        'phoneNumber'       => 'Phone number',
        'location'          => 'Location',
        'service_id'        => 'Service',
        'service_items'     => 'Service items',

        // 🟢 SessionInfoRequest
        'date' => 'Date',
        'time' => 'Time',

        // 🟢 StoreCartRequest + StoreProductRequest + UpdateCartQuantityRequest
        'product_id' => 'Product',
        'quantity'   => 'Quantity',
        'session_id' => 'Session ID',

        // 🟢 ValidateOtpRequest + VerifyOtpRequest
        'email' => 'Email',

        // 🟢 ContactUsRequest
        'message' => 'Message',
        'rating'  => 'Rating',
        'review'  => 'Review',
    ],
];