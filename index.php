<!DOCTYPE html>
<html lang="en">
<head>
  <title>Weather Sort</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<style>
   h1.heading {
    font-size: 48px;
    font-family: fantasy;
}
  .table{
    font-size: 18px;
  }
  body{
    background-image: url('./background.jpg');
  }
  .headertext{
    text-align: center;
    padding-top: 5%;
    padding-bottom: 5%;
  }
</style>
<?php


if(isset($_POST['submit']) || !isset($_POST['submit'])){
if (isset($_POST['submit'])) {
$url='https://akshaymore.net/sorting/data.php?days='.$_POST['nod'];
}
else
{
$url='https://akshaymore.net/sorting/data.xml';
}

$xml = simplexml_load_file($url) or die("feed not loading");
$i      = 0;
$tosort = array();
$tosortdate = array();

foreach ($xml->item as $item)
  {
    $tosort[$i] = (int) $item->temperature;
    $tosortdate[$i] = (string)$item->date;
    $i++;
  }

function mergesort($data)
  {
    // Only process if we're not down to one piece of data
    if (count($data) > 1)
      {
        
        // Find out the middle of the current data set and split it there to obtain to halfs
        $data_middle = round(count($data) / 2, 0, PHP_ROUND_HALF_DOWN);
        // and now for some recursive magic
        $data_part1  = mergesort(array_slice($data, 0, $data_middle));
        $data_part2  = mergesort(array_slice($data, $data_middle, count($data)));
        // Setup counters so we can remember which piece of data in each half we're looking at
        $counter1    = $counter2 = 0;
        // iterate over all pieces of the currently processed array, compare size & reassemble
        for ($i = 0; $i < count($data); $i++)
          {
            // if we're done processing one half, take the rest from the 2nd half
            if ($counter1 == count($data_part1))
              {
                $data[$i] = $data_part2[$counter2];
                ++$counter2;
                // if we're done with the 2nd half as well or as long as pieces in the first half are still smaller than the 2nd half
              }
            elseif (($counter2 == count($data_part2)) or ($data_part1[$counter1] < $data_part2[$counter2]))
              {
                $data[$i] = $data_part1[$counter1];
                ++$counter1;
              }
            else
              {
                $data[$i] = $data_part2[$counter2];
                ++$counter2;
              }
          }
      }
    return $data;
  }
// Initiate the recursive magic by calling the function once & print the output for our viewing pleasure 


function quick_sort($array)
  {
    // find array size
    $length = count($array);
    
    // base case test, if array of length 0 then just return array to caller
    if ($length <= 1)
      {
        return $array;
      }
    else
      {
        
        // select an item to act as our pivot point, since list is unsorted first position is easiest
        $pivot = $array[0];
        
        // declare our two arrays to act as partitions
        $left = $right = array();
        
        // loop and compare each item in the array to the pivot value, place item in appropriate partition
        for ($i = 1; $i < count($array); $i++)
          {
            if ($array[$i] < $pivot)
              {
                $left[] = $array[$i];
              }
            else
              {
                $right[] = $array[$i];
              }
          }
        
        // use recursion to now sort the left and right lists
        return array_merge(quick_sort($left), array(
            $pivot
        ), quick_sort($right));
      }
  }


function insertion_Sort($my_array)
  {
    for ($i = 0; $i < count($my_array); $i++)
      {
        $val = $my_array[$i];
        $j   = $i - 1;
        while ($j >= 0 && $my_array[$j] > $val)
          {
            $my_array[$j + 1] = $my_array[$j];
            $j--;
          }
        $my_array[$j + 1] = $val;
      }
    return $my_array;
  }


function bubble_Sort($my_array)
  {
    do
      {
        $swapped = false;
        for ($i = 0, $c = count($my_array) - 1; $i < $c; $i++)
          {
            if ($my_array[$i] > $my_array[$i + 1])
              {
                list($my_array[$i + 1], $my_array[$i]) = array(
                    $my_array[$i],
                    $my_array[$i + 1]
                );
                $swapped = true;
              }
          }
      } while ($swapped);
    return $my_array;
  }



class Node
  {
    private $_i;
    
    public function __construct($key)
      {
        $this->_i = $key;
      }
    
    public function getKey()
      {
        return $this->_i;
      }
  }

class Heap
  {
    private $heap_Array;
    private $_current_Size;
    
    public function __construct()
      {
        $heap_Array          = array();
        $this->_current_Size = 0;
      }
    
    // Remove item with max key 
    public function remove()
      {
        $root                = $this->heap_Array[0];
        // put last element into root
        $this->heap_Array[0] = $this->heap_Array[--$this->_current_Size];
        $this->bubbleDown(0);
        return $root;
      }
    
    // Shift process
    public function bubbleDown($index)
      {
        $larger_Child = null;
        $top          = $this->heap_Array[$index]; // save root
        while ($index < (int) ($this->_current_Size / 2)) // not on bottom row
          {
            $leftChild  = 2 * $index + 1;
            $rightChild = $leftChild + 1;
            
            // find larger child
            if ($rightChild < $this->_current_Size && $this->heap_Array[$leftChild] < $this->heap_Array[$rightChild]) // right child exists?
              {
                $larger_Child = $rightChild;
              }
            else
              {
                $larger_Child = $leftChild;
              }
            
            if ($top->getKey() >= $this->heap_Array[$larger_Child]->getKey())
              {
                break;
              }
            
            // shift child up
            $this->heap_Array[$index] = $this->heap_Array[$larger_Child];
            $index                    = $larger_Child; // go down
          }
        
        $this->heap_Array[$index] = $top; // root to index
      }
    
    public function insertAt($index, Node $newNode)
      {
        $this->heap_Array[$index] = $newNode;
      }
    
    public function incrementSize()
      {
        $this->_current_Size++;
      }
    
    public function getSize()
      {
        return $this->_current_Size;
      }
    
    public function asArray()
      {
        $arr = array();
        for ($j = 0; $j < sizeof($this->heap_Array); $j++)
          {
            $arr[] = $this->heap_Array[$j]->getKey();
          }
        
        return $arr;
      }
  }

function heapsort(Heap $Heap)
  {
    $size = $Heap->getSize();
    // "sift" all nodes, except lowest level as it has no children
    for ($j = (int) ($size / 2) - 1; $j >= 0; $j--)
      {
        $Heap->bubbleDown($j);
      }
    // sort the heap
    for ($j = $size - 1; $j >= 0; $j--)
      {
        $BiggestNode = $Heap->remove();
        // use same nodes array for sorted elements
        $Heap->insertAt($j, $BiggestNode);
      }
    
    return $Heap->asArray();
  }

$arr  = $tosort;
$Heap = new Heap();
foreach ($arr as $key => $val)
  {
    $Node = new Node($val);
    $Heap->insertAt($key, $Node);
    $Heap->incrementSize();
  }

function selection_sort($data)
  {
    for ($i = 0; $i < count($data) - 1; $i++)
      {
        $min = $i;
        for ($j = $i + 1; $j < count($data); $j++)
          {
            if ($data[$j] < $data[$min])
              {
                $min = $j;
              }
          }
        $data = swap_positions($data, $i, $min);
      }
    return $data;
  }

function swap_positions($data1, $left, $right)
  {
    $backup_old_data_right_value = $data1[$right];
    $data1[$right]               = $data1[$left];
    $data1[$left]                = $backup_old_data_right_value;
    return $data1;
  }





$time_start = microtime(true);
$data       = heapsort($Heap);
$time_end   = microtime(true);
$time[0]       = $time_end - $time_start;
$tech[0]="Heap Sort";
$result[0] = $data[0];



$time_start = microtime(true);
$data       = mergesort($tosort);
$time_end   = microtime(true);
$time[1]       = $time_end - $time_start;
$tech[1] = "Merge Sort";
$result[1] = $data[0];


$time_start = microtime(true);
$data       = selection_sort($tosort);
$time_end   = microtime(true);
$time[2]       = $time_end - $time_start;
$tech[2] = "Selection Sort";
$result[2] = $data[0];

$time_start = microtime(true);
$data       = insertion_Sort($tosort);
$time_end   = microtime(true);
$time[3]       = $time_end - $time_start;
$tech[3] = "Insertion Sort";
$result[3] = $data[0];

$time_start = microtime(true);
$data       = bubble_Sort($tosort);
$time_end   = microtime(true);
$time[4]       = $time_end - $time_start;
$tech[4] = "Bubble Sort";
$result[4] = $data[0];

$time_start = microtime(true);
$data       = quick_sort($tosort);
$time_end   = microtime(true);
$time[5]       = $time_end - $time_start;
$tech[5] = "Quick Sort";
$result[5] = $data[0];

array_multisort($tosort, $tosortdate);
array_multisort($time,$tech);
array_multisort($time,$result);

?>

<div class="container">
<div class="headertext">
<h1 class="heading">Weather Sort</h1>
<h2>Time taken by sorting algorithms to sort a weather data of more that 2000 days</h2>
<h4>You can find the dummy data <a href="./data.xml">here</a></h4>
<form action="#" method="post">
  <div class='input-group'>
  <input class="form-control" type="number" name="nod" min="1" max="10000" placeholder="Enter number of elements to sort">
  <div class="input-group-btn">
  <button class="btn btn-success" type="submit" name="submit">Go!</button>
</div>
</div>
</form>
</div>
<table class="table table-bordered">
    <thead>
      <tr>
        <th>Rank</th>
        <th>Sorting Technique</th>
        <th>Time(seconds)</th>
        <th>Date</th>
        <th>Temperature</th>
      </tr>
    </thead>
    <tbody>
<?php
for ($i=0; $i < 6; $i++) { 
echo "<tr><td>".($i+1)."</td><td>".$tech[$i]."</td><td>".number_format($time[$i],10)."</td><td>".$tosortdate[0]."</td><td>".$result[$i]."</td></tr>";
}
?>
    </tbody>
  </table>
</div>
<?php

}
unset($time);
unset($tech);
unset($tosort);
unset($tosortdate);
unset($right);
unset($left);
unset($array);
?>

</body>
</html>