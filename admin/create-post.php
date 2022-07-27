<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $userList = \Nebula\Widgets\User::allocAlias('users', ['keyword' => $request->get('keyword', '')])->getUserList() ?>
<div class="container">
    <h2 class="page-title">
        <span>新增文章</span>
    </h2>
    <form action="/post/create-post" method="post">
        <div class="form-item">
            <label class="form-label" for="title">标题</label>
            <input class="nebula-input" id="title" name="title"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="title">分类</label>
            <select class="nebula-select" id="mid" name="mid">
                <option value="0">分类一</option>
                <option value="1" selected>分类二</option>
            </select>
        </div>
        <div class="form-item">
            <label class="form-label" for="content">内容</label>
            <textarea id="content" name="content"></textarea>
        </div>
        <div class="form-tools">
            <button type="submit" class="nebula-button block">发布文章</button>
        </div>
    </form>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
