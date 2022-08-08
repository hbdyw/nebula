<?php

namespace Nebula\Widgets\Themes;

use Nebula\Helpers\Renderer;
use Nebula\Helpers\Validate;
use Nebula\Widgets\Database;
use Nebula\Widgets\Notice;
use Nebula\Widgets\Options\Method as OptionsMethod;

class Handle extends Database
{
    /**
     * 已启用列表
     *
     * @var array
     */
    private $enabledList = [];

    public function init()
    {
        $this->enabledList = OptionsMethod::factory()->get('theme');
    }

    /**
     * 启用主题
     *
     * @return void
     */
    private function enable()
    {
        $themeName = $this->params('themeName');

        if ($themeName === $this->enabledList['name']) {
            Notice::factory()->set('不能重复启用', 'warning');
            $this->response->redirect('/admin/themes.php');
        }

        // 获取主题配置
        $themeConfig = [];
        $themeFunctionsFile = NEBULA_ROOT_PATH . 'content/themes/' . $themeName . '/functions.php';
        if (file_exists($themeFunctionsFile)) {
            include $themeFunctionsFile;

            if (function_exists('theme_config')) {
                $renderer = new Renderer();
                theme_config($renderer);
                $themeConfig = $renderer->getValues();
            }
        }

        // 提交修改
        $this->db->update('options', ['value' => serialize(['name' => $themeName, 'config' => $themeConfig])], ['name' => 'theme']);

        Notice::factory()->set('启用成功', 'success');
        $this->response->redirect('/admin/themes.php');
    }

    /**
     * 更新主题配置
     *
     * @return void
     */
    private function updateConfig()
    {
        $data = $this->request->post();

        $rules = [];
        foreach (array_keys($this->enabledList['config']) as $value) {
            $rules[$value] = [['type' => 'required', 'message' => '「' . $value . '」不能为空']];
        }
        $validate = new Validate($data, $rules);
        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/theme-config.php');
        }

        foreach (array_keys($this->enabledList['config']) as $value) {
            $theme['config'][$value] = $data[$value];
        }

        // 提交修改
        $this->db->update('options', ['value' => serialize($theme)], ['name' => 'theme']);

        Notice::factory()->set('修改成功', 'success');
        $this->response->redirect('/admin/themes.php');
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 启用主题
        $this->on($action === 'enable')->enable();

        // 更新主题配置
        $this->on($action === 'update-config')->updateConfig();
    }
}
