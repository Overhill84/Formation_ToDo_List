<?php

namespace Models;

interface Crud
{

    function insert();
    function delete();
    function update();
    function selectAll();
    function select();
}
