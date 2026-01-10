<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Workflow $workflow
 * @var array $availableNodes
 * @var array $categories
 */
?>
<?php $this->assign('title', 'Creează flux de lucru'); ?>

<div class="workflows form content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-plus-circle"></i> <?= __('Creează flux de lucru') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> ' . __('Înapoi la listă'),
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
    </div>

    <?= $this->Form->create($workflow) ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Detalii flux de lucru</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('name', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Nume'],
                        'placeholder' => 'Introduceți numele fluxului de lucru',
                        'required' => true,
                    ]) ?>

                    <?= $this->Form->control('description', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Descriere'],
                        'rows' => 3,
                        'placeholder' => 'Descrieți ce face acest flux de lucru',
                    ]) ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->control('category', [
                                'type' => 'select',
                                'options' => $categories,
                                'empty' => '-- Selectează categorie --',
                                'class' => 'form-select',
                                'label' => ['class' => 'form-label', 'text' => 'Categorie'],
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('icon', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Pictogramă (clasă Font Awesome)'],
                                'placeholder' => 'fas fa-cog',
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Definiție flux de lucru</h5>
                    <button type="button" class="btn btn-sm btn-info" onclick="formatJSON()">
                        <i class="fas fa-code"></i> Formatează JSON
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Definiți fluxul de lucru folosind formatul JSON FlowScript.
                        <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Vezi ajutor sintaxă</a>
                    </div>

                    <?= $this->Form->control('definition_json', [
                        'type' => 'textarea',
                        'class' => 'form-control font-monospace',
                        'label' => false,
                        'rows' => 15,
                        'placeholder' => '{
  "initialState": {
    "completed": false
  },
  "nodes": [
    "startNode",
    {
      "processData": { "config": "value" }
    },
    [
      "checkCondition",
      {
        "success": "continueFlow",
        "failure": "handleError"
      }
    ]
  ]
}',
                        'required' => true,
                    ]) ?>

                    <div id="json-error" class="alert alert-danger mt-2 d-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="json-error-message"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Setări</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('status', [
                        'type' => 'select',
                        'options' => [
                            'draft' => 'Ciornă',
                            'active' => 'Activ',
                            'inactive' => 'Inactiv',
                        ],
                        'class' => 'form-select',
                        'label' => ['class' => 'form-label', 'text' => 'Status'],
                    ]) ?>

                    <?= $this->Form->control('is_template', [
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'label' => [
                            'class' => 'form-check-label',
                            'text' => 'Salvează ca șablon',
                        ],
                        'templates' => [
                            'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
                            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                        ],
                    ]) ?>

                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Șabloanele pot fi folosite ca punct de plecare pentru fluxuri noi
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Noduri disponibile</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="nodesAccordion">
                        <?php
                        $nodesByType = [];
                        foreach ($availableNodes as $name => $metadata) {
                            $type = $metadata['type'] ?? 'other';
                            $nodesByType[$type][$name] = $metadata;
                        }
                        ?>

                        <?php foreach ($nodesByType as $type => $nodes): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-<?= h($type) ?>">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse-<?= h($type) ?>"
                                        aria-expanded="false">
                                    <i class="fas fa-folder me-2"></i>
                                    Noduri <?= h(ucfirst($type)) ?>
                                </button>
                            </h2>
                            <div id="collapse-<?= h($type) ?>"
                                 class="accordion-collapse collapse"
                                 data-bs-parent="#nodesAccordion">
                                <div class="accordion-body">
                                    <?php foreach ($nodes as $nodeName => $nodeData): ?>
                                    <div class="node-info mb-3">
                                        <h6 class="text-primary">
                                            <code><?= h($nodeName) ?></code>
                                        </h6>
                                        <p class="mb-1 small"><?= h($nodeData['description']) ?></p>
                                        <?php if (!empty($nodeData['ai_hints']['expected_edges'])): ?>
                                        <p class="mb-1 small">
                                            <strong>Muchii:</strong>
                                            <?= h(implode(', ', $nodeData['ai_hints']['expected_edges'])) ?>
                                        </p>
                                        <?php endif; ?>
                                        <?php if (!empty($nodeData['ai_hints']['example_usage'])): ?>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                onclick="insertExample('<?= h(addslashes($nodeData['ai_hints']['example_usage'])) ?>')">
                                            <i class="fas fa-code"></i> Inserează exemplu
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <?= $this->Form->button(__('Creează flux de lucru'), [
                    'class' => 'btn btn-primary btn-lg',
                ]) ?>
                <?= $this->Html->link(__('Anulează'),
                    ['action' => 'index'],
                    ['class' => 'btn btn-outline-secondary']
                ) ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajutor sintaxă FlowScript</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Structură de bază</h6>
                <pre class="bg-light p-3"><code>{
  "initialState": { /* Valori stare inițială */ },
  "nodes": [ /* Array de elemente flux */ ]
}</code></pre>

                <h6>Tipuri de noduri</h6>
                <ul>
                    <li><strong>Nod simplu:</strong> <code>"nodeName"</code></li>
                    <li><strong>Nod cu configurare:</strong> <code>{ "nodeName": { "param": "value" } }</code></li>
                </ul>

                <h6>Structuri de control</h6>
                <p><strong>Ramificare:</strong></p>
                <pre class="bg-light p-3"><code>[
  "conditionNode",
  {
    "edge1": "targetNode1",
    "edge2": ["node1", "node2"],
    "edge3": null
  }
]</code></pre>

                <p><strong>Buclă:</strong></p>
                <pre class="bg-light p-3"><code>[
  { "whileCondition": { "condition": "state.count < 10" } },
  [
    "processItem",
    { "incrementCounter": { "field": "count" } }
  ]
]</code></pre>

                <h6>Muchii speciale</h6>
                <ul>
                    <li><code>exit</code> - Ieșire din fluxul curent</li>
                    <li><code>loopTo:nodeName</code> - Salt la un nod specific</li>
                    <li><code>next_iteration</code> - Continuă bucla (controlere buclă)</li>
                    <li><code>exit_loop</code> - Ieșire din buclă (controlere buclă)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function formatJSON() {
    const textarea = document.getElementById('definition-json');
    try {
        const json = JSON.parse(textarea.value);
        textarea.value = JSON.stringify(json, null, 2);
        hideJsonError();
    } catch (e) {
        showJsonError(e.message);
    }
}

function insertExample(example) {
    const textarea = document.getElementById('definition-json');
    const currentValue = textarea.value.trim();

    if (currentValue) {
        if (!confirm('Aceasta va înlocui definiția curentă. Continuați?')) {
            return;
        }
    }

    textarea.value = example;
    formatJSON();
}

function showJsonError(message) {
    document.getElementById('json-error').classList.remove('d-none');
    document.getElementById('json-error-message').textContent = message;
}

function hideJsonError() {
    document.getElementById('json-error').classList.add('d-none');
}

// Validate JSON on blur
document.getElementById('definition-json').addEventListener('blur', function() {
    try {
        if (this.value.trim()) {
            JSON.parse(this.value);
            hideJsonError();
        }
    } catch (e) {
        showJsonError('JSON invalid: ' + e.message);
    }
});
</script>
