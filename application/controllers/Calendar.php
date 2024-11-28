<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Event_model');
        $this->load->helper('url');
    }

    // Load the calendar view
    public function index() {
        $this->load->view('calendar_view');
    }

    // Fetch all events
    public function get_events() {
        $events = $this->Event_model->get_all_events();
        echo json_encode($events);
    }

    // Add a new event
    public function add_event() {
        $data = array(
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'start' => $this->input->post('start'),
            'end' => $this->input->post('end'),
        );

        $this->Event_model->insert_event($data);
        echo json_encode(['status' => 'success']);
    }

    // Update an event
    public function update_event($id) {
        $data = array(
            'title' => $this->input->input_stream('title'),
            'description' => $this->input->input_stream('description'),
            'start' => $this->input->input_stream('start'),
            'end' => $this->input->input_stream('end'),
        );

        $this->Event_model->update_event($id, $data);
        echo json_encode(['status' => 'success']);
    }

    // Delete an event
    public function delete_event($id) {
        $this->Event_model->delete_event($id);
        echo json_encode(['status' => 'success']);
    }
}
