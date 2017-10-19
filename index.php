<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage 
{
    public static function autoload($class) 
    {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
$obj = new main();


class main 
{

    public function __construct()
    {
        //print_r($_REQUEST);
        //set default page request when no parameters are in URL
        $pageRequest = 'homepage';
        //check if there are parameters
        if(isset($_REQUEST['page'])) 
        {
            //load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }
        //instantiate the class that is being requested
         $page = new $pageRequest;


        if($_SERVER['REQUEST_METHOD'] == 'GET') 
        {
            $page->get();
        } 
        else 
        {
            $page->post();
        }

    }

}

abstract class page 
{
    protected $html;

    public function __construct()
    {
        $this->html .= '<html>';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }
    public function __destruct()
    {
        $this->html .= '</body></html>';
        stringFunctions::printThis($this->html);
    }

    public function get() 
    {
        echo 'default get message';
    }

    public function post() 
    {
        print_r($_POST);
    }
}

class homepage extends page 
{

    public function get()
    {
        $form = '<form method="post" enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="fileToUpload"><br><br>';
        $form .= '<input type="submit" value="Upload File" name="submit">';
        $form .= '</form> ';
        $this->html .= '<h2>Upload Form</h2>';
        $this->html .= '<h2>Choose csv file to upload</h2>';
        $this->html .= $form;

    }

    public function post() 
    {
        $file_location = "store/"; // this is the directory where the file will be stored.
        $file_change = $file_location . basename($_FILES["fileToUpload"]["name"]); //location of the file to be uploaded. 
        $uploadOk = 1;
        $File_type = pathinfo($file_change,PATHINFO_EXTENSION);
        $File_name = pathinfo($file_change,PATHINFO_BASENAME);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file_change);
        header('Location: index.php?page=htmlTable&filename='.$file_change);
    }
}

class htmlTable extends page 
{
    public function get()
    {
        $html="";
        $change = fopen($_GET['filename'], "r");// reading the csv file
        $html.='<table border="1">';
        while (!feof($change)) // reading till the end of the file
        {
            $var=fgetcsv($change); // reading the contents of the file
            $counter=count($var);
            $html.='<tr>';
            for($i=0;$i<$counter;$i++)
            {
                $html .= '<td>'.$var[$i].'</td>';
            }
           $html.='</tr>';

        }
       $html.='</table>';
       print($html); // printing the html table
       fclose($change); // closing an open file
    }

   
}



class stringFunctions 
{
     static public function printThis($Text1) 
     {
        return print($Text1);
     }
     static public function stringLength($Text2) 
     {
        return strLen($Text2);
     }  
}


?>
