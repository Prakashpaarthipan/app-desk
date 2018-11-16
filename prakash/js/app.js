var loaddate = new Vue({
	el:'#profile_form',
	data:{
		txt_profile:'',
		empname:'',
		comname:'',
		empsrno:'',
		img:null
	},
	http: {
            emulateJSON: true,
            emulateHTTP: true
    },

	methods:{
		 loaddata(){
			alert(this.txt_profile);
			var name= 'test name';
			var id = this.txt_profile;
			var array = ['BMW','VOLVO'];

			 var bodyFormData = new FormData();
			 bodyFormData.set('id', this.txt_profile);
			 bodyFormData.set('name', 'raja');

	       axios
	       .post('prakash/axios/axios_load.php?profile=emp',
	         {
	        	name,id,array
	        	//bodyFormData
	          
	        })
	        .then(function (data) {
			    //console.log(response);
			    //this.loaddate.empname = data.data;
			    var pars = data.data;

			    const empobj = JSON.stringify(pars);
			    const p = JSON.parse(empobj);
			    //console.log(p);
			   // console.log(empobj);
			    //console.log(data.data.EMPNAME);
			    this.loaddate.empname = p.EMPNAME;
			    this.loaddate.empsrno = p.EMPSRNO;
			    this.loaddate.brncode = p.BRNCODE;
			    this.loaddate.comname = p.COMNAME;
			    
			    //console.log(id);
			    this.loaddate.getImg(id,this.loaddate.brncode);

			    //console.log(p.EMPNAME);
			    //console.log(p.EMPSRNO);
			    //console.log(p.BRNCODE);

			  })
			.catch(function (error) {
			    console.log(error);
			  })

	        //await alert(this.txt_profile);
		},

		getImg:function(id,brn){
			//console.log('clicked!');
			
			axios({
				url:'profile_img.php?branch='+brn+'&action=user_profile_img&profile_img='+id+'',
				method:'GET',
				responseType: 'blob'
			})
			.then(function (response){
				this.loaddate.img='profile_img.php?branch='+brn+'&action=user_profile_img&profile_img='+id+'';
				
				console.log(response);
			})
			.catch(function(error){
				console.log(error);
			})
		}
	}

});
// $('#profile_img').html('<img src="profile_img.php?branch='+branch+'&action=user_profile_img&profile_img='+ececode[0]+'" style="width:200px; height:200px; border:1px solid #a0a0a0; text-align:center; border:0px;" onerror='alert('Image missing')' />');
//

var attend = new Vue({

		el:"#attend",
		data:{
			empname:'',
			empcode:'',
			present:'',
			absent:'',
			leave:'',
			txt_code:'',
			date:new Date().toISOString().slice(0,7)
			//toISOString().split('T')[0]

		},
		
		
		methods:{
			loadattend(){
				var dates = this.date.split("-");
					this.empcode = '';
					this.empname = '';
					this.present = '';
					this.absent = '';
					this.leave = '';

				axios({
					url:'prakash/axios/axios_load.php?attend=emp',
					method:'POST',
					data:{id:this.txt_code,
						year:dates[0],
						month:dates[1],
					}
				})
				.then(function(response){
					console.log(response);
					this.attend.empcode = response.data[0];
					this.attend.empname = response.data[1];
					this.attend.present = response.data[2];
					this.attend.absent = response.data[3];
					this.attend.leave = response.data[4];

					//console.log(response.data[0]);
				})
				.catch(function(error){
					console.log(error);
				})
			}

		}
});

       