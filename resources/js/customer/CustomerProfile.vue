<template>
    <div>
        <main class="container">
            <div class="starter-template text-left py-5 px-2 mt-3">
                <h1>My Profile</h1>
            </div>

            <div class="alert alert-warning" v-if="loaded">
                <span class="spinner-border text-warning mr-3"></span>Please wait... loading your information...
            </div>

            <div class="alert alert-warning" v-if="isSavingProfile">
                <span class="spinner-border text-warning mr-3"></span>Please wait... saving your profile...
            </div>

            <form @submit.prevent="onSubmitForm" v-if="!loaded">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First name</label>
                            <input v-model="firstname" type="text" class="form-control" placeholder="Firstname" />
                            <div class="text-danger">{{ firstname_error }}</div>
                        </div>

                        <div class="form-group">
                            <label>Last name</label>
                            <input v-model="lastname" type="text" class="form-control" placeholder="Lastname" />
                            <div class="text-danger">{{ lastname_error }}</div>
                        </div>

                        <div class="form-group">
                            <label>Email address</label>
                            <input v-model="email" type="text" class="form-control" placeholder="Email address" />
                            <div class="text-danger">{{ email_error }}</div>
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" class="form-control" @change="onFileChanged" />
                            <div v-if="customer.image !== ''">
                                <img :src="`uploads/customer/${customer.id}/thumb/${customer.image}`" class="" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</template>

<script>
export default {
    data() {
        return {
            firstname: "",
            firstname_error: "",
            lastname: "",
            lastname_error: "",
            email: "",
            email_error: "",
            customer: [],
            selectedFile: null,
            loaded: true,
            isSavingProfile: false,
        };
    },
    created() {
        this.onPopulateData();
    },
    methods: {
        onFileChanged(event) {
            this.selectedFile = event.target.files[0];
        },
        onSubmitForm() {
            this.isSavingProfile = true;
            this.$query("updateProfile", {
                file: this.selectedFile,
                customer: {
                    firstname: this.firstname,
                    lastname: this.lastname,
                    email: this.email,
                },
            }).then((res) => {
                this.onPopulateData();
                this.isSavingProfile = false;
                this.$swal("Success!", "Your profile was successfully updated!", "success");
            });
        },
        onPopulateData() {
            this.$query("customer").then((res) => {
                this.loaded = false;
                this.customer = res.data.data.customer;
                this.firstname = this.customer.firstname;
                this.lastname = this.customer.lastname;
                this.email = this.customer.email;
            });
        },
    },
};
</script>
