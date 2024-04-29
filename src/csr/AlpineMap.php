<?php

namespace HtmlFirst\atlaAS\csr;

abstract class AlpineMap {
    private String_ $string_;
    public function __construct() {
        $this->string_ = new String_;
    }
    private string $transition = 'x-transition';
    private string $data = 'x-data';
    private string $bind = 'x-bind:';
    private string $on = 'x-on:';
    private string $text = 'x-text';
    private string $html = 'x-html';
    private string $model = 'x-model';
    private string $show = 'x-show';
    protected function data(array $value): static {
        $this->string_->set_attr($this->data, \json_encode($value));
        return $this;
    }
    protected function bind(string $attr, string $value): static {
        $this->string_->set_attr($this->bind . $attr, $value);
        return $this;
    }
    protected function on(string $attr, string $value): static {
        $this->string_->set_attr($this->on . $attr, $value);
        return $this;
    }
    protected function text(string $value): static {
        $this->string_->set_attr($this->text, $value);
        return $this;
    }
    protected function html(string $value): static {
        $this->string_->set_attr($this->html, $value);
        return $this;
    }
    protected function model(string $value): static {
        $this->string_->set_attr($this->model, $value);
        return $this;
    }
    protected function show(string $value): static {
        $this->string_->set_attr($this->show, $value);
        return $this;
    }
    /**
     * transition
     *
     * @param  ""|"enter"|"enter-start"|"enter-end"|"leave"|"leave-start"|"leave-end" $directive
     */
    protected function transition(string $directive = '', string $value = ''): static {
        $valid_directives = ['enter', 'enter-start', 'enter-end', 'leave', 'leave-start', 'leave-end'];
        if (!in_array($directive, $valid_directives)) {
            $directive = '';
        }
        if ($directive === '') {
            $directive = '';
        } else {
            $directive = ":$directive";
        }
        $this->string_->set_attr($this->transition . $directive, $value);
        return $this;
    }
}
