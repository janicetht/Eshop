let currPage = 1;
let isRequest = true;

let url = new URL(window.location.href);
let catid = url.searchParams.get("CATID");

let flag = 0;

function load_product() {
    $.ajax({
        url:"fetch_prod_by_page.php",
        method:"POST",
        dataType:"json",
        data: {catid:catid, pageNum: 1},
        success:function(data)
        {
			isRequest = false;
			$('#Product').append(data.Product);
			//$('#Product').html(data);
        }
    });
};

if (flag == 0)
{
	load_product();
	flag++;
}

$("#Product").scroll(function() {
    if($("#Product").scrollTop() >= $("#Product").prop('scrollHeight') - $("#Product").height() - 10 ) {
    if(!isRequest) {
      isRequest = true;
      $.ajax({
        url:"fetch_prod_by_page.php",
        method:"POST",
        dataType:"json",
        data: {catid:catid, pageNum: currPage+1},
        success:function(data)
        {
			currPage++;
			isRequest = false;
			$('#Product').append(data.Product);
			//$('#Product').html(data);
        },
        fail: function() {
			isRequest = false;
			console.error("fail request page" + (currPage+1));
        }
      });
    }
    }
});