<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BooksController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Auth');
        $this->auth->check();
        $this->load->model('Book_model');
        $this->load->helper('response');
    }

    // GET /api/books
    // public function index() {
    //     $books = $this->Book_model->getAll();
    //     json_response($books);
    // }

    public function index() {

        $page     = (int) $this->input->get('page') ?: 1;
        $perPage  = (int) $this->input->get('per_page') ?: 15;

        if ($perPage > 50) {
            $perPage = 50; // safety limit
        }

        $offset = ($page - 1) * $perPage;

        $filters = [
            'title'  => $this->input->get('title'),
            'author' => $this->input->get('author'),
            'genre'  => $this->input->get('genre')
        ];

        $books = $this->Book_model->getPaginated($filters, $perPage, $offset);
        $total = $this->Book_model->countAll($filters);

        json_response([
            'data' => $books,
            'pagination' => [
                'page'       => $page,
                'per_page'   => $perPage,
                'total'      => $total,
                'total_pages'=> ceil($total / $perPage)
            ]
        ]);
    }


    // POST /api/books
    public function create() {
        $this->auth->requireRole('librarian');

        $this->Book_model->create([
            'title'            => $this->input->post('title'),
            'author'           => $this->input->post('author'),
            'genre'            => $this->input->post('genre'),
            'isbn'             => $this->input->post('isbn'),
            'copies_total'     => $this->input->post('copies_total'),
            'copies_available' => $this->input->post('copies_total')
        ]);

        json_response(['message' => 'Book created']);
    }

    // PUT /api/books/{id}
    public function update($id) {

        // Role check
        $this->auth->requireRole('librarian');

        $book = $this->Book_model->findById($id);
        if (!$book) {
            json_error('Book not found', 404);
        }

        $data = [
            'title'  => $this->input->post('title'),
            'author' => $this->input->post('author'),
            'genre'  => $this->input->post('genre')
        ];

        // Remove null values (partial update)
        $data = array_filter($data);

        $this->Book_model->updateBook($id, $data);

        json_response(['message' => 'Book updated successfully']);
    }

    // DELETE /api/books/{id}
    public function delete($id) {

        // Role check
        $this->auth->requireRole('librarian');

        $book = $this->Book_model->findById($id);
        if (!$book) {
            json_error('Book not found', 404);
        }

        $this->Book_model->deleteBook($id);

        json_response(['message' => 'Book deleted successfully']);
    }


}
