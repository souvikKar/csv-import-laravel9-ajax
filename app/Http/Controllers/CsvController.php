<?php

namespace App\Http\Controllers;

use App\Models\CsvFile;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function upload(Request $request)
    {
        session_start();

        $error = '';

        $html = '';

        if ($_FILES['file']['name'] != '') {
            $file_array = explode(".", $_FILES['file']['name']);

            $extension = end($file_array);

            if ($extension == 'csv') {
                $file_data = fopen($_FILES['file']['tmp_name'], 'r');

                $file_header = fgetcsv($file_data);

                $html .= '<table class="table table-bordered"><tr>';

                for ($count = 0; $count < count($file_header); $count++) {
                    $html .= '
        <th>
            <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
            <option value="">Set Count Data</option>
            <option value="first_name">First Name</option>
            <option value="last_name">Last Name</option>
            <option value="email">Email</option>
            </select>
        </th>
        ';
                }

                $html .= '</tr>';

                $limit = 0;

                while (($row = fgetcsv($file_data)) !== false) {
                    $limit++;

                    if ($limit < 6) {
                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '</tr>';
                    }

                    $temp_data[] = $row;
                }

                $_SESSION['file_data'] = $temp_data;

                $html .= '
        </table>
        <br />
        <div align="right">
        <button type="button" name="import" id="import" class="btn btn-success" disabled>Import</button>
        </div>
        <br />
        ';
            } else {
                $error = 'Only <b>.csv</b> file allowed';
            }
        } else {
            $error = 'Please Select CSV File';
        }

        $output = array(
            'error' => $error,
            'output' => $html,
        );

        echo json_encode($output);
    }

    public function import(Request $request)
    {
        try {
            session_start();
            //dd($request);
            //dd($_SESSION['file_data']);
            $file_data = $_SESSION['file_data'];
            unset($_SESSION['file_data']);
            foreach ($file_data as $row) {
                $data[] = '("' . $row[$_POST["first_name"]] . '", "' . $row[$_POST["last_name"]] . '", "' . $row[$_POST["email"]] . '")';
                if (isset($data)) {
                    CsvFile::create([
                        'first_name' => $row[$_POST["first_name"]],
                        'last_name' => $row[$_POST["last_name"]],
                        'email' => $row[$_POST["email"]]
                    ]);
                }
            }

            echo 'Data Imported Successfully';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
