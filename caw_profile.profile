<?php

/**
 * @file
 * caw_profile.profile
 */

/**
 * Implements hook_install_tasks().
 */
function caw_profile_install_tasks(&$install_state) {
  return ['caw_profile_final_task' => []];
}

/**
 * Perform final tasks after the profile has completed installing.
 *
 * @param array $install_state
 *   Current install state.
 */
function caw_profile_final_task(array &$install_state) {
  \Drupal::service('plugin.manager.install_tasks')->runTasks($install_state);
}

function mikes() {
  $field_names = [
    'common' => [
      'caw_benefits_fulltime_cost' => 'Full-Time Employee * Contribution Per Pay Period',
      'caw_benefits_parttime_cost' => 'Part-Time Employee * Contribution Per Pay Period',
      'caw_benefits_deductible' => 'Deductible',
      'caw_benefits_coinsurance' => 'Coinsurance',
      'caw_benefits_annual_max' => 'Annual maximum',
      'caw_benefits_lifetime_max' => 'Lifetime maximum',
      'caw_benefits_xray' => 'X-rays',
    ],
    'medical' => [
      'caw_benefits_med_preauth_req' => 'Pre-Authorization Requirement',
      'caw_benefits_med_care_mgmt' => 'Care Management',
      'caw_benefits_med_copay' => 'Office co-pay',
      'caw_benefits_med_maternity_stay' => 'Maternity Hospital Stay',
      'caw_benefits_med_baby_exam' => 'Baby\'s First Exam',
      'caw_benefits_med_birthing' => 'Birthing Centers',
      'caw_benefits_med_midwives' => 'Midwives',
      'caw_benefits_med_prenatal' => 'Prenatal Visits',
      'caw_benefits_med_delivery' => 'Doctor Delivery Charge',
      'caw_benefits_med_preg_term' => 'Pregnancy Termination',
      'caw_benefits_med_mental_health' => 'Mental Health',
      'caw_benefits_med_autism' => 'Autism',
      'caw_benefits_med_substance' => 'Substance Abuse',
      'caw_benefits_med_acupuncture' => 'Acupuncture',
      'caw_benefits_med_allergy_test' => 'Allergy Tests',
      'caw_benefits_med_allergy_treat' => 'Allergy Treatment',
      'caw_benefits_med_alt_medicine' => 'Alternative Medicine',
      'caw_benefits_med_abulance' => 'Ambulance charges',
      'caw_benefits_med_ct_scan' => 'CT Scans',
      'caw_benefits_med_chiro' => 'Chiropractors',
      'caw_benefits_med_christian_prac' => 'Christian Science Practitioners',
      'caw_benefits_med_cosmetic' => 'Cosmetic Surgery',
      'caw_benefits_med_dental' => 'Dental Treatment',
      'caw_benefits_med_er' => 'Emergency Room',
      'caw_benefits_med_urgent' => 'Urgent Care',
      'caw_benefits_med_hearing' => 'Hearing Care',
      'caw_benefits_med_home_health' => 'Home Health Care',
      'caw_benefits_med_hospice' => 'Hospice Care',
      'caw_benefits_med_hopsital_stay' => 'Hospital Stay',
      'caw_benefits_med_infertility' => 'Infertility Treatment',
      'caw_benefits_med_lab' => 'Laboratory Charges',
      'caw_benefits_med_mri' => 'Magnetic resonance imaging - MRI',
      'caw_benefits_med_durable_equip' => 'Durable Medical Equipment',
      'caw_benefits_med_occupational' => 'Occupational Therapy',
      'caw_benefits_med_transpants' => 'Organ Transplants',
      'caw_benefits_med_surgery_md' => 'Surgery : Physician Services',
      'caw_benefits_med_pt' => 'Physical Therapy',
      'caw_benefits_med_nursing' => 'Skilled Nursing',
      'caw_benefits_med_surgery_facil' => 'Surgery : Facility Charges',
      'caw_benefits_med_speech' => 'Speech Therapy',
      'caw_benefits_med_tubal' => 'Tubal Ligation',
      'caw_benefits_med_vasectomy' => 'Vasectomy',
      'caw_benefits_med_vision' => 'Vision care',
      'caw_benefits_med_pharmacy' => 'Pharmacy (Retail)',
      'caw_benefits_med_mail_rx' => 'Mail order drug program',
      'caw_benefits_med_birth_control' => 'Birth Control Pills',
      'caw_benefits_med_adult_physical' => 'Physical exams for adults',
      'caw_benefits_med_child_psycial' => 'Physical exams for children',
      'caw_benefits_med_pap_smears' => 'Pap smears',
      'caw_benefits_med_mammograms' => 'Mammograms',
      'caw_benefits_med_immunizations' => 'Immunizations',
      'caw_benefits_med_psa' => 'Prostate Specific Antigen test - PSA',
      'caw_benefits_med_well_woman' => 'Well-woman visits',
    ],
    'dental' => [
      'caw_benefits_dent_cleanings' => 'Cleanings',
      'caw_benefits_dent_flouride' => 'Fluoride treatments',
      'caw_benefits_dent_exam' => 'Routine exams',
      'caw_benefits_dent_sealant' => 'Sealants',
      'caw_benefits_dent_ortho' => 'Orthodontia',
      'caw_benefits_dent_retainer' => 'Retainers',
      'caw_benefits_dent_anesthesia' => 'Anesthesia',
      'caw_benefits_dent_bridges' => 'Bridges',
      'caw_benefits_dent_crown' => 'Crown',
      'caw_benefits_dent_denture' => 'Dentures',
      'caw_benefits_dent_extraction' => 'Extractions',
      'caw_benefits_dent_filling' => 'Fillings',
      'caw_benefits_dent_gingivectomy' => 'Gingivectomy',
      'caw_benefits_dent_gold' => 'Gold restorations',
      'caw_benefits_dent_implant' => 'Implants',
      'caw_benefits_dent_inlay' => 'Inlays',
      'caw_benefits_dent_onlay' => 'Onlays',
      'caw_benefits_dent_oral_surg' => 'Oral surgery',
      'caw_benefits_dent_perio_surg' => 'Periodontal surgery',
      'caw_benefits_dent_rx' => 'Prescription drugs',
      'caw_benefits_dent_root' => 'Root canals',
      'caw_benefits_dent_space' => 'Space maintainers',
      'caw_benefits_dent_splint' => 'Splinting',
      'caw_benefits_dent_tmj' => 'TMJ (Temporomandibular joint syndrome)',
    ],
    'vision' => [
      'caw_benefits_vision_exam' => 'Vision Exam',
      'caw_benefits_vision_lenses' => 'Lenses',
      'caw_benefits_vision_frames' => 'Frames',
      'caw_benefits_vision_contacts' => 'Elective Contact Lenses',
      'caw_benefits_vision_lowvis' => 'Low-Vision Services',
      'caw_benefits_vision_extras' => 'Extras',
      'caw_benefits_vision_urgent' => 'Medical & Urgent Eye Care',
      'caw_benefits_vision_intl' => 'Out of Country Access',
    ],
  ];


  foreach ($field_names as $group => $fields) {
    if (empty(\Drupal::configFactory()
      ->listAll("migrate_plus.migration.caw_$group"))) {
      continue;
    }
    $config = \Drupal::configFactory()
      ->getEditable("migrate_plus.migration.caw_$group");
    $process = [];
    foreach ($fields as $field_name => $label) {
      $process[$field_name] = [
        [
          'plugin' => 'skip_on_empty',
          'source' => $field_name,
          'method' => 'process',
        ],
        [
          'plugin' => 'str_replace',
          'regex' => TRUE,
          'search' => '>[ }]?<',
          'replace' => '><',
        ],
        [
          'plugin' => 'str_replace',
          'regex' => TRUE,
          'search' => '<p[^>]*?>',
          'replace' => '',
        ],
        [
          'plugin' => 'str_replace',
          'search' => '</p>',
          'replace' => '\n',
        ],
      ];
    }
    $config->set('process', $process)->save();
  }
}
