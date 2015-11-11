<?php

abstract class Base_Report extends MY_Controller
{
    abstract public function export_csv();
}
