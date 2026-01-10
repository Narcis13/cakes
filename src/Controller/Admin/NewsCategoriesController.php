<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Utility\Text;

/**
 * NewsCategories Controller
 *
 * @property \App\Model\Table\NewsCategoriesTable $NewsCategories
 * @method \App\Model\Entity\NewsCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NewsCategoriesController extends AppController
{
    /**
     * Initialization hook method.
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->NewsCategories->find()
            ->order(['NewsCategories.name' => 'ASC']);

        $newsCategories = $this->paginate($query);

        // Get news count per category
        foreach ($newsCategories as $category) {
            $category->news_count = $this->NewsCategories->News->find()
                ->where(['category_id' => $category->id])
                ->count();
        }

        $this->set(compact('newsCategories'));
    }

    /**
     * View method
     *
     * @param string|null $id News Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $newsCategory = $this->NewsCategories->get($id, [
            'contain' => ['News' => function ($q) {
                return $q->order(['News.created' => 'DESC'])->limit(10);
            }],
        ]);

        $newsCount = $this->NewsCategories->News->find()
            ->where(['category_id' => $id])
            ->count();

        $this->set(compact('newsCategory', 'newsCount'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $newsCategory = $this->NewsCategories->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Generate slug if not provided
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = Text::slug(strtolower($data['name']));
            }

            $newsCategory = $this->NewsCategories->patchEntity($newsCategory, $data);

            if ($this->NewsCategories->save($newsCategory)) {
                $this->Flash->success(__('The news category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news category could not be saved. Please, try again.'));
        }

        $this->set(compact('newsCategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id News Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $newsCategory = $this->NewsCategories->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Generate slug if empty
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = Text::slug(strtolower($data['name']));
            }

            $newsCategory = $this->NewsCategories->patchEntity($newsCategory, $data);

            if ($this->NewsCategories->save($newsCategory)) {
                $this->Flash->success(__('The news category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news category could not be saved. Please, try again.'));
        }

        $this->set(compact('newsCategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id News Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $newsCategory = $this->NewsCategories->get($id);

        // Check if category has news articles
        $newsCount = $this->NewsCategories->News->find()
            ->where(['category_id' => $id])
            ->count();

        if ($newsCount > 0) {
            $this->Flash->error(__(
                'This category cannot be deleted as it has {0} news articles. ' .
                'Please reassign or delete the articles first.',
                $newsCount,
            ));

            return $this->redirect(['action' => 'index']);
        }

        if ($this->NewsCategories->delete($newsCategory)) {
            $this->Flash->success(__('The news category has been deleted.'));
        } else {
            $this->Flash->error(__('The news category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
