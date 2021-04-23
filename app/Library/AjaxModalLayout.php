<?php
/**
 * Created by PhpStorm.
 * User: Tarinder
 * Date: 1/11/2018
 * Time: 4:43 PM
 */

namespace App\Library;


use Illuminate\Support\Facades\View;

class AjaxModalLayout extends Ajax
{
    const CALLBACK_MODAL_LAYOUT = 'loadModalLayout';

    /**
     * @param $view
     * @param array $data
     * @return $this
     */
    public function loadModalLayout($view, $data = [])
    {
        $view = View::make($view, $data);
        $content = $view->render();
        $sdata = [
            'content' => $content
        ];
        if (isset($data['title'])) {
            $sdata['title'] = $data['title'];
        }
        if (isset($data['size'])) {
            $sdata['size'] = $data['size'];
        }
        $view = View::make('layouts.modal-popup-layout', $sdata);
        $html = $view->render();
        $this->appendParam('html', $html);
        $this->jscallback(self::CALLBACK_MODAL_LAYOUT);
        return $this;
    }
}