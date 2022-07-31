<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $userList = \Nebula\Widgets\User::allocAlias('users', ['keyword' => $request->get('keyword', '')])->getUserList() ?>
<div class="container">
    <h2 class="nebula-title">新增文章</h2>
    <form class="nebula-form" id="postForm" action="/post/create-post" method="POST">
        <div class="form-item">
            <label class="form-label" for="title">标题</label>
            <input class="nebula-input" id="title" name="title" value="<?= \Nebula\Helpers\Cookie::get('title', '') ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="tid">分类</label>
            <select class="nebula-select" id="tid" name="tid" value="<?= \Nebula\Helpers\Cookie::get('title', '') ?>">
                <option value="0">分类一</option>
                <option value="1" selected>分类二</option>
            </select>
        </div>
        <div class="form-item">
            <label class="form-label" for="content">内容</label>
            <input type="text" name="content" id="content" hidden>
            <div class="standalone-container">
                <div id="editor-container"></div>
            </div>
        </div>
        <div class="form-tools">
            <div class="nebula-button-group">
                <button type="button" class="nebula-button">保存草稿</button>
                <button type="button" class="nebula-button">预览</button>
                <button id="postSubmitButton" type="button" class="nebula-button">发布文章</button>
            </div>
        </div>
    </form>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<script>
    // 富文本编辑器
    const quill = new Quill('#editor-container', {
        modules: {
            formula: true,
            syntax: true,
            toolbar: [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                },
                'bold', 'italic', 'underline', 'strike',
                {
                    'align': []
                },
                {
                    'indent': '-1'
                }, {
                    'indent': '+1'
                },
                'blockquote', 'code-block',
                {
                    'color': []
                }, {
                    'background': []
                },
                {
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                },
                {
                    'script': 'sub'
                }, {
                    'script': 'super'
                },
                'link', 'image', 'video', 'formula',
                'clean',
            ],
        },
        theme: 'snow'
    });

    // 表单回填
    quill.setContents(<?= \Nebula\Helpers\Cookie::get('content', '') ?>);

    document.querySelector('#postSubmitButton').addEventListener('click', function() {
        const postForm = document.querySelector('#postForm');
        postForm.content.value = JSON.stringify(quill.getContents());
        postForm.submit();
    });
</script>
<?php require __DIR__ . '/footer.php'; ?>