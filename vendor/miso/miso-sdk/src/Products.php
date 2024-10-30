<?php

namespace Miso;

class Products {

    protected $helpers;

    public function __construct(Core $helpers) {
        $this->helpers = $helpers;
    }

    public function ids($args = []) {
        // TODO: catch 404
        return $this->helpers->get('products/_ids')['ids'];
    }

    public function upload($records) {
        return $this->helpers->post('products', ['data' => $records]);
    }

    public function delete($ids) {
        // TODO: take both string and array
        return $this->helpers->post('products/_delete', [
            'data' => [
                'product_ids' => $ids,
            ],
        ]);
    }

}
