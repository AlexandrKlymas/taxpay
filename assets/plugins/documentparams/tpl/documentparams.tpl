<div id="DocumentParams-[+moduleId+]_[+serviceId+]" class="tab-page tab-content-[+moduleId+]_[+serviceId+]" style="width:99.5%;-moz-box-sizing: border-box; box-sizing: border-box;">
    <h2 
		class="tab" 
		data-link="tabLink[+moduleId+]_[+serviceId+]" 
		id="DocumentParamsTab tabLink[+moduleId+]_[+serviceId+]"
	>[+tabName+]</h2>
    <p>[+tabContent+]</p>
     <iframe id="modeuleId_[+moduleId+]_[+serviceId+]" src="/manager/index.php?a=112&id=[+moduleId+]&tabaction=[+tabAction+]&documentid=[+documentId+]&service=[+serviceId+]&region=[+regionId+]" style="width:100%;height:700px;" scrolling="auto" frameborder="0"></iframe>
    <script>       
        document.querySelector('[data-link="tabLink[+moduleId+]_[+serviceId+]"]').addEventListener('click', function () {
            document.querySelector('.tab-content-[+moduleId+]_[+serviceId+] iframe').contentWindow.location.reload();
        }, false);
    </script>
</div>