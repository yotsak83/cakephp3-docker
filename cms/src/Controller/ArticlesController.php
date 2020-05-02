<?php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');

        $this->Auth->allow(['tags']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        if (in_array($action, ['add', 'tags'])) {
            return true;
        }

        $slug = $this->request->getParam('pass.0');
        if (!$slug) {
            return false;
        }

        $articles = $this->Articles->findBySlug($slug)->first();

        return $articles->user_id === $user['id'];
    }

    public function index()
    {
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    public function view($slug = null)
    {
        // 最初のレコードを取得するか NotFoundException を投げるかのいずれか
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            $article->user_id = $this->Auth->user('id');

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $tags = $this->Articles->Tags->find('list');
        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    public function edit($slug)
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData(), [
               'accessibleFields' => ['user_id' => false]
            ]);
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }
        $tags = $this->Articles->Tags->find('list');

        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function tags()
    {
        $tags = $this->request->getParam('pass');

        $articles = $this->Articles->find('tagged', [
            'tags' => $tags,
        ]);

        $this->set([
            'articles' => $articles,
            'tags' => $tags,
        ]);
    }

}
