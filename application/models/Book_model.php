<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_model extends CI_Model {

    // public function getAll() {
    //     return $this->db->get('books')->result();
    // }

    public function create($data) {
        return $this->db->insert('books', $data);
    }

    public function findById($id) {
        return $this->db->get_where('books', ['id' => $id])->row();
    }

    public function updateBook($id, $data) {
        return $this->db->where('id', $id)->update('books', $data);
    }

    public function deleteBook($id) {
        return $this->db->where('id', $id)->delete('books');
    }

    public function getPaginated($filters, $limit, $offset) {

        if (!empty($filters['title'])) {
            $this->db->like('title', $filters['title']);
        }

        if (!empty($filters['author'])) {
            $this->db->like('author', $filters['author']);
        }

        if (!empty($filters['genre'])) {
            $this->db->where('genre', $filters['genre']);
        }

        return $this->db
            ->limit($limit, $offset)
            ->get('books')
            ->result();
    }

    public function countAll($filters) {

        if (!empty($filters['title'])) {
            $this->db->like('title', $filters['title']);
        }

        if (!empty($filters['author'])) {
            $this->db->like('author', $filters['author']);
        }

        if (!empty($filters['genre'])) {
            $this->db->where('genre', $filters['genre']);
        }

        return $this->db->count_all_results('books');
    }


}
