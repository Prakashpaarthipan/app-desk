<form method="post">
 
Employee Code<input type="text" name="txt_employee_code" id="txt_employee_code" ><br><br>
Task Detail<input type="text" name="txt_task_details" id="txt_task_details"><br><br>
Enter Time<input type="time" name="txt_task_from_time" id="txt_task_from_time"><br><br>
Out Time<input type="time" name="txt_task_to_time" id="txt_task_to_time"><br><br>
   


<input type="submit" name="submit" value="submit">
   </form>
<?php
 if(isset($_POST['submit']))
{
	$employeecode=$_POST['txt_employee_code'];
    $taskdetail=$_POST['txt_task_details'];
    $entertime=$_POST['txt_task_from_time'];
    $outtime=$_POST['txt_task_to_time'];
}
?>
<table border="1">
<tr><th>EMPLOYEE CODE</th><th>TASK DETAIL</th><th>ENTER TIME</th><th>OUTER TIME</th></tr>
<tr>
<td><?php echo "$employeecode";?></td>
<td><?php echo "$taskdetail";?></td>
<td><?php echo "$entertime";?></td>
<td><?php echo "$outtime";?></td>
</tr>
</table> 
</body>
</html>

		

   
