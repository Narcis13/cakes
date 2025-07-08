<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Workflows seed.
 * 
 * Creates sample hospital workflows to demonstrate the system
 */
class WorkflowsSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Patient Admission Workflow',
                'description' => 'Handles the complete patient admission process including registration, insurance verification, and room assignment',
                'definition_json' => json_encode([
                    'initialState' => [
                        'patientId' => null,
                        'admissionComplete' => false,
                        'insuranceVerified' => false,
                        'roomAssigned' => false,
                    ],
                    'nodes' => [
                        ['log', ['message' => 'Starting patient admission process', 'level' => 'info']],
                        
                        // Check if patient exists
                        [
                            'branch',
                            [
                                'conditions' => [
                                    'existing' => 'state.patientId != null',
                                    'new' => 'state.patientId == null',
                                ],
                            ],
                        ],
                        [
                            'existing' => 'updatePatientRecord',
                            'new' => 'createPatientRecord',
                        ],
                        
                        // Verify insurance
                        'verifyInsurance',
                        [
                            'checkInsuranceResult',
                            [
                                'verified' => 'proceedWithAdmission',
                                'rejected' => 'handleInsuranceIssue',
                                'manual' => 'requestManualVerification',
                            ],
                        ],
                        
                        // Proceed with admission
                        'proceedWithAdmission',
                        'checkRoomAvailability',
                        [
                            'roomAvailable',
                            [
                                'available' => 'assignRoom',
                                'unavailable' => 'addToWaitingList',
                            ],
                        ],
                        
                        // Final steps
                        'assignRoom',
                        'notifyStaff',
                        'generateAdmissionDocuments',
                        ['setFlag', ['flag' => 'admissionComplete', 'value' => true]],
                        
                        ['log', ['message' => 'Patient admission completed successfully', 'level' => 'info']],
                    ],
                ]),
                'version' => 1,
                'status' => 'active',
                'category' => 'patient',
                'icon' => 'fas fa-user-plus',
                'created_by' => 1,
                'is_template' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Emergency Response Protocol',
                'description' => 'Coordinates emergency response including alert distribution, resource allocation, and status tracking',
                'definition_json' => json_encode([
                    'initialState' => [
                        'emergencyCode' => null,
                        'severity' => null,
                        'responseTeamAlerted' => false,
                        'resourcesAllocated' => false,
                    ],
                    'nodes' => [
                        ['log', ['message' => 'Emergency protocol activated', 'level' => 'warning']],
                        
                        // Assess emergency
                        'assessEmergency',
                        [
                            'determineSeverity',
                            [
                                'critical' => 'activateCodeRed',
                                'high' => 'activateCodeYellow',
                                'medium' => 'standardResponse',
                            ],
                        ],
                        
                        // Critical path
                        'activateCodeRed',
                        'alertAllStaff',
                        'notifyDepartmentHeads',
                        'prepareEmergencyRooms',
                        
                        // Resource allocation loop
                        [
                            ['forEach', ['items' => 'state.requiredResources', 'as' => 'resource']],
                            [
                                'checkResourceAvailability',
                                'allocateResource',
                                'updateResourceStatus',
                            ],
                        ],
                        
                        // Monitor and update
                        [
                            ['whileCondition', ['condition' => 'state.emergencyActive == true']],
                            [
                                'monitorSituation',
                                ['wait', ['seconds' => 30]],
                                'updateStatus',
                                'checkIfResolved',
                            ],
                        ],
                        
                        // Completion
                        'generateIncidentReport',
                        'notifyManagement',
                        ['log', ['message' => 'Emergency protocol completed', 'level' => 'info']],
                    ],
                ]),
                'version' => 1,
                'status' => 'active',
                'category' => 'emergency',
                'icon' => 'fas fa-ambulance',
                'created_by' => 1,
                'is_template' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Staff Onboarding Process',
                'description' => 'Manages new staff onboarding including document collection, training scheduling, and access provisioning',
                'definition_json' => json_encode([
                    'initialState' => [
                        'staffId' => null,
                        'documentsCollected' => false,
                        'trainingScheduled' => false,
                        'accessProvisioned' => false,
                        'onboardingComplete' => false,
                    ],
                    'nodes' => [
                        ['log', ['message' => 'Starting staff onboarding process', 'level' => 'info']],
                        
                        // Create staff record
                        'createStaffProfile',
                        
                        // Document collection
                        'requestRequiredDocuments',
                        'waitForDocuments',
                        [
                            'checkDocumentStatus',
                            [
                                'complete' => 'proceedWithOnboarding',
                                'incomplete' => 'sendDocumentReminder',
                                'issues' => 'escalateToHR',
                            ],
                        ],
                        
                        // Department assignment
                        'proceedWithOnboarding',
                        'assignToDepartment',
                        'notifyDepartmentHead',
                        
                        // Training setup
                        'determineRequiredTraining',
                        'scheduleTrainingSessions',
                        'createTrainingCalendar',
                        
                        // Access provisioning
                        'generateAccessCard',
                        'createSystemAccounts',
                        'assignRolePermissions',
                        
                        // Welcome process
                        'sendWelcomePackage',
                        'scheduleOrientation',
                        ['setFlag', ['flag' => 'onboardingComplete', 'value' => true]],
                        
                        ['log', ['message' => 'Staff onboarding completed', 'level' => 'info']],
                    ],
                ]),
                'version' => 1,
                'status' => 'active',
                'category' => 'staff',
                'icon' => 'fas fa-user-tie',
                'created_by' => 1,
                'is_template' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('workflows');
        $table->insert($data)->save();
        
        // Insert sample nodes
        $this->insertSampleNodes();
    }
    
    /**
     * Insert sample workflow nodes
     *
     * @return void
     */
    private function insertSampleNodes(): void
    {
        $nodes = [
            [
                'name' => 'createPatientRecord',
                'type' => 'action',
                'category' => 'patient',
                'description' => 'Creates a new patient record in the system',
                'metadata_json' => json_encode([
                    'ai_hints' => [
                        'purpose' => 'Create patient records',
                        'when_to_use' => 'When registering a new patient',
                        'expected_edges' => ['success', 'error'],
                    ],
                ]),
                'handler_class' => 'App\Workflow\Node\Hospital\CreatePatientRecordNode',
                'icon' => 'fas fa-user-plus',
                'is_builtin' => false,
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'verifyInsurance',
                'type' => 'integration',
                'category' => 'billing',
                'description' => 'Verifies patient insurance coverage',
                'metadata_json' => json_encode([
                    'ai_hints' => [
                        'purpose' => 'Insurance verification',
                        'when_to_use' => 'During patient admission or billing',
                        'expected_edges' => ['verified', 'rejected', 'manual'],
                    ],
                ]),
                'handler_class' => 'App\Workflow\Node\Hospital\VerifyInsuranceNode',
                'icon' => 'fas fa-shield-alt',
                'is_builtin' => false,
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'assignRoom',
                'type' => 'action',
                'category' => 'facility',
                'description' => 'Assigns an available room to a patient',
                'metadata_json' => json_encode([
                    'ai_hints' => [
                        'purpose' => 'Room assignment',
                        'when_to_use' => 'When admitting a patient',
                        'expected_edges' => ['assigned', 'unavailable'],
                    ],
                ]),
                'handler_class' => 'App\Workflow\Node\Hospital\AssignRoomNode',
                'icon' => 'fas fa-bed',
                'is_builtin' => false,
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'notifyStaff',
                'type' => 'action',
                'category' => 'communication',
                'description' => 'Sends notifications to relevant staff members',
                'metadata_json' => json_encode([
                    'ai_hints' => [
                        'purpose' => 'Staff notification',
                        'when_to_use' => 'When staff needs to be informed of events',
                        'expected_edges' => ['sent', 'failed'],
                    ],
                ]),
                'handler_class' => 'App\Workflow\Node\Hospital\NotifyStaffNode',
                'icon' => 'fas fa-bell',
                'is_builtin' => false,
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        
        $table = $this->table('workflow_nodes');
        $table->insert($nodes)->save();
    }
}