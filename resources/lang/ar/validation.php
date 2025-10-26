<?php

return [

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما يكون :other هو :value.',
    'active_url' => ':attribute ليس رابطاً صحيحاً.',
    'after' => 'يجب أن يكون :attribute تاريخاً بعد :date.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخاً بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات فقط.',
    'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'ascii' => 'يجب أن يحتوي :attribute على أحرف ورموز أحادية البايت فقط.',
    'before' => 'يجب أن يكون :attribute تاريخاً قبل :date.',
    'before_or_equal' => 'يجب أن يكون :attribute تاريخاً قبل أو يساوي :date.',
    'between' => [
        'array' => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max.',
        'file' => 'يجب أن يكون حجم :attribute بين :min و :max كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'string' => 'يجب أن يكون طول :attribute بين :min و :max حرف.',
    ],
    'boolean' => 'يجب أن يكون :attribute صحيح أو خاطئ.',
    'can' => 'يحتوي :attribute على قيمة غير مصرح بها.',
    'confirmed' => 'تأكيد :attribute غير مطابق.',
    'contains' => 'فقد :attribute قيمة مطلوبة.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => ':attribute ليس تاريخاً صحيحاً.',
    'date_equals' => 'يجب أن يكون :attribute تاريخاً مساوياً لـ :date.',
    'date_format' => ':attribute لا يطابق الصيغة :format.',
    'decimal' => 'يجب أن يحتوي :attribute على :decimal منازل عشرية.',
    'declined' => 'يجب رفض :attribute.',
    'declined_if' => 'يجب رفض :attribute عندما يكون :other هو :value.',
    'different' => 'يجب أن يكون :attribute و :other مختلفين.',
    'digits' => 'يجب أن يكون :attribute :digits رقم.',
    'digits_between' => 'يجب أن يكون :attribute بين :min و :max رقم.',
    'dimensions' => ':attribute له أبعاد صورة غير صحيحة.',
    'distinct' => ':attribute له قيمة مكررة.',
    'doesnt_end_with' => 'يجب ألا ينتهي :attribute بأحد القيم التالية: :values.',
    'doesnt_start_with' => 'يجب ألا يبدأ :attribute بأحد القيم التالية: :values.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح.',
    'ends_with' => 'يجب أن ينتهي :attribute بأحد القيم التالية: :values.',
    'enum' => ':attribute المحدد غير صحيح.',
    'exists' => ':attribute المحدد غير صحيح.',
    'extensions' => 'يجب أن يكون :attribute ملف من نوع: :values.',
    'file' => 'يجب أن يكون :attribute ملف.',
    'filled' => ':attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'array' => 'يجب أن يحتوي :attribute على أكثر من :value عنصر.',
        'file' => 'يجب أن يكون حجم :attribute أكثر من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute أكثر من :value.',
        'string' => 'يجب أن يكون طول :attribute أكثر من :value حرف.',
    ],
    'gte' => [
        'array' => 'يجب أن يحتوي :attribute على :value عنصر أو أكثر.',
        'file' => 'يجب أن يكون حجم :attribute أكثر من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute أكثر من أو تساوي :value.',
        'string' => 'يجب أن يكون طول :attribute أكثر من أو يساوي :value حرف.',
    ],
    'hex_color' => 'يجب أن يكون :attribute لون سادس عشري صحيح.',
    'image' => 'يجب أن يكون :attribute صورة.',
    'in' => ':attribute المحدد غير صحيح.',
    'in_array' => ':attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون :attribute رقم صحيح.',
    'ip' => 'يجب أن يكون :attribute عنوان IP صحيح.',
    'ipv4' => 'يجب أن يكون :attribute عنوان IPv4 صحيح.',
    'ipv6' => 'يجب أن يكون :attribute عنوان IPv6 صحيح.',
    'json' => 'يجب أن يكون :attribute نص JSON صحيح.',
    'list' => 'يجب أن يكون :attribute قائمة.',
    'lowercase' => 'يجب أن يكون :attribute أحرف صغيرة.',
    'lt' => [
        'array' => 'يجب أن يحتوي :attribute على أقل من :value عنصر.',
        'file' => 'يجب أن يكون حجم :attribute أقل من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute أقل من :value.',
        'string' => 'يجب أن يكون طول :attribute أقل من :value حرف.',
    ],
    'lte' => [
        'array' => 'يجب ألا يحتوي :attribute على أكثر من :value عنصر.',
        'file' => 'يجب أن يكون حجم :attribute أقل من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute أقل من أو تساوي :value.',
        'string' => 'يجب أن يكون طول :attribute أقل من أو يساوي :value حرف.',
    ],
    'mac_address' => 'يجب أن يكون :attribute عنوان MAC صحيح.',
    'max' => [
        'array' => 'يجب ألا يحتوي :attribute على أكثر من :max عنصر.',
        'file' => 'يجب ألا يكون حجم :attribute أكثر من :max كيلوبايت.',
        'numeric' => 'يجب ألا تكون قيمة :attribute أكثر من :max.',
        'string' => 'يجب ألا يكون طول :attribute أكثر من :max حرف.',
    ],
    'max_digits' => 'يجب ألا يحتوي :attribute على أكثر من :max رقم.',
    'mimes' => 'يجب أن يكون :attribute ملف من نوع: :values.',
    'mimetypes' => 'يجب أن يكون :attribute ملف من نوع: :values.',
    'min' => [
        'array' => 'يجب أن يحتوي :attribute على الأقل على :min عنصر.',
        'file' => 'يجب أن يكون حجم :attribute على الأقل :min كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'string' => 'يجب أن يكون طول :attribute على الأقل :min حرف.',
    ],
    'min_digits' => 'يجب أن يحتوي :attribute على الأقل على :min رقم.',
    'missing' => 'يجب أن يكون :attribute مفقود.',
    'missing_if' => 'يجب أن يكون :attribute مفقود عندما يكون :other هو :value.',
    'missing_unless' => 'يجب أن يكون :attribute مفقود إلا إذا كان :other هو :value.',
    'missing_with' => 'يجب أن يكون :attribute مفقود عندما يكون :values موجود.',
    'missing_with_all' => 'يجب أن يكون :attribute مفقود عندما تكون :values موجودة.',
    'multiple_of' => 'يجب أن يكون :attribute مضاعف للعدد :value.',
    'not_in' => ':attribute المحدد غير صحيح.',
    'not_regex' => 'صيغة :attribute غير صحيحة.',
    'numeric' => 'يجب أن يكون :attribute رقم.',
    'password' => [
        'letters' => 'يجب أن يحتوي :attribute على حرف واحد على الأقل.',
        'mixed' => 'يجب أن يحتوي :attribute على حرف كبير وحرف صغير على الأقل.',
        'numbers' => 'يجب أن يحتوي :attribute على رقم واحد على الأقل.',
        'symbols' => 'يجب أن يحتوي :attribute على رمز واحد على الأقل.',
        'uncompromised' => ':attribute المعطى ظهر في تسرب بيانات. يرجى اختيار :attribute مختلف.',
    ],
    'present' => 'يجب أن يكون :attribute موجود.',
    'present_if' => 'يجب أن يكون :attribute موجود عندما يكون :other هو :value.',
    'present_unless' => 'يجب أن يكون :attribute موجود إلا إذا كان :other هو :value.',
    'present_with' => 'يجب أن يكون :attribute موجود عندما يكون :values موجود.',
    'present_with_all' => 'يجب أن يكون :attribute موجود عندما تكون :values موجودة.',
    'prohibited' => ':attribute محظور.',
    'prohibited_if' => ':attribute محظور عندما يكون :other هو :value.',
    'prohibited_unless' => ':attribute محظور إلا إذا كان :other في :values.',
    'prohibits' => ':attribute يحظر وجود :other.',
    'regex' => 'صيغة :attribute غير صحيحة.',
    'required' => ':attribute مطلوب.',
    'required_array_keys' => 'يجب أن يحتوي :attribute على مدخلات لـ: :values.',
    'required_if' => ':attribute مطلوب عندما يكون :other هو :value.',
    'required_if_accepted' => ':attribute مطلوب عندما يتم قبول :other.',
    'required_if_declined' => ':attribute مطلوب عندما يتم رفض :other.',
    'required_unless' => ':attribute مطلوب إلا إذا كان :other في :values.',
    'required_with' => ':attribute مطلوب عندما يكون :values موجود.',
    'required_with_all' => ':attribute مطلوب عندما تكون :values موجودة.',
    'required_without' => ':attribute مطلوب عندما لا يكون :values موجود.',
    'required_without_all' => ':attribute مطلوب عندما لا تكون أي من :values موجودة.',
    'same' => 'يجب أن يتطابق :attribute مع :other.',
    'size' => [
        'array' => 'يجب أن يحتوي :attribute على :size عنصر.',
        'file' => 'يجب أن يكون حجم :attribute :size كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute :size.',
        'string' => 'يجب أن يكون طول :attribute :size حرف.',
    ],
    'starts_with' => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values.',
    'string' => 'يجب أن يكون :attribute نص.',
    'timezone' => 'يجب أن يكون :attribute منطقة زمنية صحيحة.',
    'unique' => ':attribute مُستخدم من قبل.',
    'uploaded' => 'فشل في تحميل :attribute.',
    'uppercase' => 'يجب أن يكون :attribute أحرف كبيرة.',
    'url' => 'يجب أن يكون :attribute رابط صحيح.',
    'ulid' => 'يجب أن يكون :attribute ULID صحيح.',
    'uuid' => 'يجب أن يكون :attribute UUID صحيح.',

    'custom' => [
        'password' => [
            'regex' => 'يجب أن تحتوي كلمة المرور على أحرف كبيرة وصغيرة وأرقام ورموز خاصة.',
        ],
        'phone' => [
            'min_digits' => 'يجب أن يحتوي رقم الهاتف على :min رقم على الأقل.',
            'max_digits' => 'يجب ألا يحتوي رقم الهاتف على أكثر من :max رقم.',
        ],
        'childPhoto' => [
            'image' => 'يجب أن تكون صورة الطفل ملف صورة صحيح.',
            'mimes' => 'يجب أن تكون صورة الطفل من نوع: PNG، JPG، أو GIF.',
            'max' => 'يجب ألا يزيد حجم صورة الطفل عن 2 ميجابايت.',
        ],
        'voiceRecording' => [
            'mimes' => 'يجب أن يكون التسجيل الصوتي من نوع: MP3، WAV، أو M4A.',
            'max' => 'يجب ألا يزيد حجم التسجيل الصوتي عن 10 ميجابايت.',
        ],
        'age' => [
            'integer' => 'يجب أن يكون العمر رقم صحيح.',
            'min' => 'يجب أن يكون العمر سنة واحدة على الأقل.',
            'max' => 'يجب ألا يزيد العمر عن 18 سنة.',
        ],
        'fullName' => [
            'min' => 'يجب أن يكون الاسم الكامل 3 أحرف على الأقل.',
            'max' => 'يجب ألا يزيد الاسم الكامل عن 25 حرف.',
        ],
        'schoolName' => [
            'max' => 'يجب ألا يزيد اسم المدرسة عن 25 حرف.',
        ],
        'textDescription' => [
            'max' => 'يجب ألا يزيد الوصف النصي عن 10000 حرف.',
        ],
        'email' => [
            'unique' => 'هذا البريد الإلكتروني مُستخدم من قبل.',
            'exists' => 'البريد الإلكتروني غير موجود في النظام.',
        ],
        'slot_date' => [
            'after_or_equal' => 'يجب أن يكون تاريخ الحجز اليوم أو بعده.',
        ],
        'slot_time' => [
            'date_format' => 'يجب أن يكون وقت الحجز بصيغة ساعة:دقيقة (مثال: 14:30).',
        ],
        'role' => [
            'in' => 'يجب أن يكون الدور إما "parent" أو "public-user".',
        ],
        'organizationName' => [
            'required' => 'اسم المؤسسة مطلوب.',
            'string' => 'يجب أن يكون اسم المؤسسة نص صحيح.',
        ],
        'contactPersonName' => [
            'required' => 'اسم الشخص المسؤول مطلوب.',
            'string' => 'يجب أن يكون اسم الشخص المسؤول نص صحيح.',
        ],
        'phoneNumber' => [
            'required' => 'رقم الهاتف مطلوب.',
            'string' => 'يجب أن يكون رقم الهاتف نص صحيح.',
        ],
        'location' => [
            'required' => 'الموقع مطلوب.',
            'string' => 'يجب أن يكون الموقع نص صحيح.',
        ],
        'service_id' => [
            'required' => 'الخدمة مطلوبة.',
            'exists' => 'الخدمة المحددة غير موجودة.',
        ],
        'service_items' => [
            'required' => 'عناصر الخدمة مطلوبة.',
            'array' => 'يجب أن تكون عناصر الخدمة قائمة.',
        ],
        'service_items.*' => [
            'exists' => 'أحد عناصر الخدمة المحددة غير موجود.',
        ],
        'otp' => [
            'required' => 'رمز التحقق مطلوب.',
            'numeric' => 'يجب أن يكون رمز التحقق أرقام فقط.',
        ],
        'date' => [
            'required' => 'التاريخ مطلوب.',
            'date' => 'يجب أن يكون التاريخ صحيح.',
        ],
        'time' => [
            'required' => 'الوقت مطلوب.',
            'date_format' => 'يجب أن يكون الوقت بصيغة ساعة:دقيقة (مثال: 14:30).',
        ],
        'product_id' => [
            'required' => 'المنتج مطلوب.',
            'exists' => 'المنتج المحدد غير موجود.',
        ],
        'quantity' => [
            'required' => 'الكمية مطلوبة.',
            'integer' => 'يجب أن تكون الكمية رقم صحيح.',
            'min' => 'يجب أن تكون الكمية واحد على الأقل.',
        ],
        'session_id' => [
            'string' => 'يجب أن يكون معرف الجلسة نص صحيح.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // 🟢 CognifyParentRequest
        'name'        => 'الاسم',
        'email'       => 'البريد الإلكتروني',
        'phone'       => 'رقم الهاتف',
        'password'    => 'كلمة المرور',
        'address'     => 'العنوان',
        'governorate' => 'المحافظة',
        'role'        => 'الدور',

        // 🟢 CognifyChildRequest
        'childPhoto'       => 'صورة الطفل',
        'fullName'         => 'الاسم الكامل',
        'age'              => 'العمر',
        'schoolName'       => 'اسم المدرسة',
        'schoolAddress'    => 'عنوان المدرسة',
        'homeAddress'      => 'عنوان المنزل',
        'gender'           => 'النوع',
        'textDescription'  => 'الوصف النصي',
        'voiceRecording'   => 'تسجيل صوتي',
        'foodAllergies'    => 'حساسية الطعام',
        'environmentalAllergies' => 'الحساسية البيئية',
        'severityLevels'   => 'مستويات الحدة',
        'medicationAllergies' => 'حساسية الأدوية',
        'medicalConditions' => 'الحالات الطبية',

        // 🟢 EmployeeRequest
        'first_name'  => 'الاسم الأول',
        'middle_name' => 'الاسم الأوسط',
        'last_name'   => 'اسم العائلة',
        'date_of_birth' => 'تاريخ الميلاد',
        'gender' => 'النوع',
        'nationality' => 'الجنسية',
        'street_address' => 'عنوان الشارع',
        'highest_degree' => 'أعلى شهادة',
        'institution'    => 'المؤسسة التعليمية',
        'graduation_year' => 'سنة التخرج',
        'other_institution' => 'مؤسسة أخرى',
        'other_degree_title' => 'عنوان شهادة أخرى',
        'other_degree_institution' => 'مؤسسة الشهادة الأخرى',
        'other_degree_year' => 'سنة الشهادة الأخرى',
        'current_position_title' => 'المسمى الوظيفي الحالي',
        'current_institution'    => 'المؤسسة الحالية',
        'current_from'           => 'تاريخ بدء العمل الحالي',
        'current_to'             => 'تاريخ انتهاء العمل الحالي',
        'current_responsibilities' => 'المسؤوليات الحالية',
        'previous_position_title' => 'المسمى الوظيفي السابق',
        'previous_institution'    => 'المؤسسة السابقة',
        'previous_from'           => 'تاريخ بدء العمل السابق',
        'previous_to'             => 'تاريخ انتهاء العمل السابق',
        'previous_responsibilities' => 'المسؤوليات السابقة',
        'total_teaching_experience' => 'إجمالي خبرة التدريس',
        'special_needs_experience'  => 'خبرة في ذوي الاحتياجات الخاصة',
        'shadow_teacher_experience' => 'خبرة كمعلم ظل',
        'arabic_proficiency'  => 'إجادة اللغة العربية',
        'english_proficiency' => 'إجادة اللغة الإنجليزية',
        'french_proficiency'  => 'إجادة اللغة الفرنسية',
        'italian_proficiency' => 'إجادة اللغة الإيطالية',
        'computer_skills'     => 'مهارات الحاسب',
        'training_title'      => 'عنوان التدريب',
        'training_date'       => 'تاريخ التدريب',
        'professional_memberships' => 'العضويات المهنية',
        'motivation'          => 'الدوافع',
        'suitability'         => 'مدى الملاءمة',

        // 🟢 ForgetPasswordRequest + ResetPasswordRequest
        'otp'   => 'رمز التحقق',

        // 🟢 ObservationChildCaseRequest
        'child_id'   => 'معرف الطفل',
        'session_id' => 'معرف الجلسة',
        'slot_date'  => 'تاريخ الحجز',
        'slot_time'  => 'وقت الحجز',

        // 🟢 ParentLoginRequest
        'password' => 'كلمة المرور',

        // 🟢 PartnerRequest
        'organizationName'  => 'اسم المؤسسة',
        'contactPersonName' => 'اسم الشخص المسؤول',
        'phoneNumber'       => 'رقم الهاتف',
        'location'          => 'الموقع',
        'service_id'        => 'الخدمة',
        'service_items'     => 'عناصر الخدمة',

        // 🟢 SessionInfoRequest
        'date' => 'التاريخ',
        'time' => 'الوقت',

        // 🟢 StoreCartRequest + StoreProductRequest + UpdateCartQuantityRequest
        'product_id' => 'المنتج',
        'quantity'   => 'الكمية',
        'session_id' => 'معرف الجلسة',

        // 🟢 ValidateOtpRequest + VerifyOtpRequest
        'email' => 'البريد الإلكتروني',

        // 🟢 ContactUsRequest
        'message' => 'الرسالة',
        'rating'  => 'التقييم',
        'review'  => 'المراجعة',
    ],

];
