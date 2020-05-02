<h1>記事一覧</h1>
<p><?= $this->Html->link("記事の追加", ['action' => 'add']) ?></p>
<table>
    <tr>
        <th>タイトル</th>
        <th>作成日時</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article): ?>
    <tr>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('編集', ['action' => 'edit', $article->slug]) ?>
            <?= $this->Form->postLink(
                '削除',
                ['action' => 'delete', $article->slug],
                ['confirm' => 'よろしいですか?'])
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
