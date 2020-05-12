<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verify Your Account</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="code" class="col-md-4 col-form-label text-md-right">Enter Code</label>

                        <div class="row">
                            <div class="col-md-6">
                                <input v-model="code" type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                              <button @click="verifyCode" class="btn btn-primary">Submit Code</button>
                            </div>
                        </div>
                        
                    </div>

                    Before proceeding, please enter your verification code that was sent to your email or mobile.

                    If you did not receive the code,
		            <button @click="resend" type="submit" class="btn btn-link p-0 m-0 align-baseline">click here to request another</button>.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                code:''
            }
        },
        methods:{
            resend(){
                console.log('Clicked')
                 let loader = this.$loading.show({
                            container: this.fullPage ? null : this.$refs.formContainer,
                            onCancel: this.onCancel,
                            color: '#c91010',
                            loader: 'bars',
                            width: 80,
                            height: 100,
                })
                axios.post('/resend')
                    .then((res)=>{
                        loader.hide();
                        toastr.success('Verification code sent!')
                    }).catch((err)=>{
                        toastr.error(err)
                    })
            },
            verifyCode(){
                axios.post('/verifyUser',{
                    code:this.code
                }).then((res)=>{
                    // this.$router.push('/home')
                    // window.location.href = '/home';
                    toastr.success('Account Verified!')
                }).catch((err)=>{
                    toastr.error('Invalid Code')
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
