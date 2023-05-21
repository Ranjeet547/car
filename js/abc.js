$("#rentbtn").click(function(){
    $.ajax(
    'rent-item.php',
{
  success: function(data) {
    alert('AJAX call was successful!');
    alert('Data from the server' + data);
  },
  error: function() {
    alert('There was some error performing the AJAX call!');
  }
}
);

});