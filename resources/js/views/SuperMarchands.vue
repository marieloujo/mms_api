<template>

    <div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Super marchands</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Super marchands</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau super marchand</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                                <div class="card-body" style="height: 550px;">
                                    <div class="form-group row">
                                        <label for="nom" class="col-sm-2 col-form-label">Nom</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nom" name="nom" required autocomplete="nom" autofocus placeholder="Nom" v-model="form.user.nom">
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('nom')" v-text="form.errors.get('nom')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="prenom" class="col-sm-2 col-form-label">Prenom</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="prenom" name="prenom" required autocomplete="prenom" autofocus placeholder="Prenom" v-model="form.user.prenom">
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('prenom')" v-text="form.errors.get('prenom')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="sexe" class="col-sm-2 col-form-label">Sexe</label>
                                        <div class="col-sm-10">
                                            <select v-model="form.user.sexe" name="sexe" id="sexe" class="form-control" required="true" >
                                                <option value="">Choisissez le sexe</option>
                                                <option value="Masculin">Masculin</option>
                                                <option value="Feminin">Feminin</option>
                                            </select>
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('sexe')" v-text="form.errors.get('sexe')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="telephone" class="col-sm-2 col-form-label">Telephone</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="telephone" placeholder="Telephone" v-model="form.user.telephone" required>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('telephone')" v-text="form.errors.get('telephone')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="email" placeholder="Email" v-model="form.user.email" required>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="situation_matrimoniale" class="col-sm-2 col-form-label">Situation matrimoniale</label>
                                        <div class="col-sm-10">
                                            <select v-model="form.user.situation_matrimoniale" name="situation_matrimoniale" id="situation_matrimoniale" class="form-control" required="true" >
                                                <option value="">Choisissez la situation matrimoniale</option>
                                                <option value="Célibataire">Célibataire</option>
                                                <option value="Marié(e)">Marié(e)</option>
                                                <option value="Divorcé(e)">Divorcé(e)</option>
                                                <option value="Veuf(ve)">Veuf(ve)</option>
                                                <option value="Concubin(e)">Concubin(e)</option>
                                            </select>
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('sexe')" v-text="form.errors.get('sexe')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="adresse" class="col-sm-2 col-form-label">Adresse</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="adresse" placeholder="Adresse" v-model="form.user.adresse" required>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('adresse')" v-text="form.errors.get('adresse')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="departement" class="col-sm-2 col-form-label">Departement</label>
                                        <div class="col-sm-10">
                                            <select v-model="form.user.commune.departement.id" name="departement_id" id="departement_id" class="form-control" required="true" @click.prevent="getDepartements">
                                                <option value="">Choisissez le departement</option>

                                                <option v-for="departement in departements" :key="departement.id" :value="departement.id" >{{departement.nom}} 
                                                </option>
                                            </select>
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('departement_id')" v-text="form.errors.get('departement_id')"></span>
                                            
                                        </div>
                                    </div>
                                    
                                        <div class="form-group row">
                                            <label for="commune" class="col-sm-2 col-form-label">Commune</label>
                                            <div class="col-sm-10">
                                                <select v-model="form.user.commune.id" name="commune_id" id="commune_id" class="form-control" required="true" @click.prevent ="getCommunes(form.user.commune.departement.id)">
                                                    <option value="">Choisissez la commune</option>

                                                    <option v-for="commune in communes" :key="commune.id" :value="commune.id" >{{commune.nom}} 
                                                    </option>
                                                </select>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('commune_id')" v-text="form.errors.get('commune_id')"></span>
                                                
                                            </div>
                                        </div>
                                    
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info" :disabled="form.errors.any()">Ajouter marchand</button>
                                </div>
                                <!-- /.card-footer -->
                            </form>
                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">LISTE DES SUPER MARCHANDS</h3>

                                <div class="card-tools">
                                    <form class="form-horizontal" @submit.prevent="onSearch" @keydown="form.errors.clear()">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Departement</th>
                                            <th>Nom</th>
                                            <th>Prenom</th>
                                            <th>Sexe</th>
                                            <th>Date naissance</th>
                                            <th>Adresse</th>
                                            <th>Telephone</th>
                                            <th>Email</th>
                                            <th>Situation matrimoniale</th>
                                            <th>Commission</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="supermarchand in supermarchands.data "  :key="supermarchand.id" >

                                                <td >{{supermarchand.id}}</td>
                                                <td>{{supermarchand.user.commune.departement.nom}}</td>
                                                <td>{{supermarchand.user.nom}}</td>
                                                <td>{{supermarchand.user.prenom}}</td>
                                                <td>{{supermarchand.user.sexe}}</td>
                                                <td>{{supermarchand.user.date_naissance}}</td>
                                                <td>{{supermarchand.user.adresse}}</td>
                                                <td>{{supermarchand.user.telephone}}</td>
                                                <td>{{supermarchand.user.email}}</td>
                                                <td>{{supermarchand.user.situation_matrimoniale}}</td>
                                                <td>{{supermarchand.commission}}</td>
                                        </tr>
                                        <tr  >

                                                <td >1</td>
                                                <td>ALIBORI</td>
                                                <td>COFFI</td>
                                                <td>Comlan</td>
                                                <td>Masculin</td>
                                                <td>03-09-1992</td>
                                                <td> Addresse </td>
                                                <td>99943454</td>
                                                <td>coffi.comlan@gmail.com</td>
                                                <td>Concubin</td>
                                                <td>10000</td>
                                            
                                        </tr>


                                        <tr  >

                                                <td >2</td>
                                                <td>ATLANTIQUE</td>
                                                <td>TON</td>
                                                <td>Firmin</td>
                                                <td>Masculin</td>
                                                <td>03-09-1995</td>
                                                <td> Addresse </td>
                                                <td>99943454</td>
                                                <td>firmin.ton@gmail.com</td>
                                                <td>Concubin</td>
                                                <td>3000</td>
                                            
                                        </tr>


                                        <tr  >

                                                <td >3</td>
                                                <td>ATACORA</td>
                                                <td>HOUKPO</td>
                                                <td>Côme</td>
                                                <td>Masculin</td>
                                                <td>03-09-1996</td>
                                                <td> Addresse </td>
                                                <td>99943454</td>
                                                <td>h.Côme@gmail.com</td>
                                                <td>Concubin</td>
                                                <td>10000</td>
                                            
                                        </tr>


                                        <tr  >

                                                <td >4</td>
                                                <td>BORGOU</td>
                                                <td>OKEY</td>
                                                <td>Arnaud</td>
                                                <td>Masculin</td>
                                                <td>03-09-1991</td>
                                                <td> Addresse </td>
                                                <td>99943454</td>
                                                <td>coffi.comlan@gmail.com</td>
                                                <td>Concubin</td>
                                                <td>10000</td>
                                            
                                        </tr>

                                        <tr  >

                                                <td >5</td>
                                                <td>COLLINE</td>
                                                <td>ADAA</td>
                                                <td>Rigobert</td>
                                                <td>Ma</td>
                                                <td>03-09-1994</td>
                                                <td> Addresse </td>
                                                <td>98908454</td>
                                                <td>ada.a@gmail.com</td>
                                                <td>Célibataire</td>
                                                <td>15000</td>
                                            
                                        </tr>


                                        <tr  >

                                                <td >6</td>
                                                <td>COUFFO</td>
                                                <td>Jean</td>
                                                <td>Viané</td>
                                                <td>Masculin</td>
                                                <td>03-09-1996</td>
                                                <td> Addresse </td>
                                                <td>98340454</td>
                                                <td>olga.girl@gmail.com</td>
                                                <td>Célibataire</td>
                                                <td>15000</td>
                                            
                                        </tr>

                                        <tr  >

                                                <td >7</td>
                                                <td>DONGA</td>
                                                <td>MENSAH</td>
                                                <td>Olga</td>
                                                <td>Feminin</td>
                                                <td>03-09-1996</td>
                                                <td> Addresse </td>
                                                <td>98900454</td>
                                                <td>olga.girl@gmail.com</td>
                                                <td>Célibataire</td>
                                                <td>1500</td>
                                            
                                        </tr>

                                        <tr  >

                                                <td >8</td>
                                                <td>LITTORAL</td>
                                                <td>DOMINGO</td>
                                                <td>Idi</td>
                                                <td>Masculin</td>
                                                <td>03-09-1997</td>
                                                <td> Addresse </td>
                                                <td>67123454</td>
                                                <td>idiMail@gmail.com </td>
                                                <td>marié</td>
                                                <td>4500</td>
                                            
                                        </tr>

                                        <tr  >

                                                <td >9</td>
                                                <td>MONO</td>
                                                <td>HOUEHA</td>
                                                <td>Melvine</td>
                                                <td>Feminin</td>
                                                <td>03-09-1998</td>
                                                <td> Addresse </td>
                                                <td>67123454</td>
                                                <td>houehamelvine@gmail.com </td>
                                                <td>marié</td>
                                                <td>500</td>
                                            
                                        </tr>
                                        <tr  >

                                                <td >10</td>
                                                <td>OUÉMÉ</td>
                                                <td>TONOU</td>
                                                <td>Cintia</td>
                                                <td>Feminin</td>
                                                <td>03-09-1983</td>
                                                <td> Addresse </td>
                                                <td>67123454</td>
                                                <td>Cintia@gmail.com </td>
                                                <td>marié</td>
                                                <td>500</td>
                                            
                                        </tr>
                                        <tr>
                                                <td >11</td>
                                                <td>PLATEAU</td>
                                                <td>AZA</td>
                                                <td>Célestin</td>
                                                <td>Masculin</td>
                                                <td>03-09-1980</td>
                                                <td> Addresse </td>
                                                <td>67123454</td>
                                                <td>Pas d'email </td>
                                                <td>Concubin(e)</td>
                                                <td>500</td>
                                            
                                        </tr>
                                        <tr  >

                                                <td >12</td>
                                                <td>ZOU</td>
                                                <td>AGBOSSOU</td>
                                                <td>Géoffroid</td>
                                                <td>Masculin</td>
                                                <td>03-09-1997</td>
                                                <td> Addresse </td>
                                                <td>67123454</td>
                                                <td>Pas d'email </td>
                                                <td>marié</td>
                                                <td>3500</td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /.content -->

    </div>

</template>

<script>
        import axios from 'axios';
    export default {

        data() {

            return {
                supermarchands: [],
                users: [],
                
                departements: [],
                communes: [],
                form: new Form({
                    'id':'',
                    'matricule':'',
                    'commission': 0,
                    'user':{
                        'id': '',
                        'nom': '',
                        'sexe': '',
                        'login': '',
                        'actif': true,
                        'email': '',
                        'prenom': '',
                        'adresse': '',
                        'prospect': false,
                        'telephone': '',
                        'date_naissance': '',
                        'situation_matrimoniale': '',
                        'commune':{
                            'id': 0,
                            'nom': '',
                            'departement':{
                                'id': 0,
                                'nom': '',
                            }
                        }
                    }
                })
            }

        },

        created() {
            axios.get('http://localhost:8000/api/supermarchands/')
                .then(data => this.supermarchands = data.data)
                .catch(error => console.log(error)); 
        },

        methods: {
            onSubmit(){
                //this.form.password_confirmation = this.form.password; // Temp for this form only.
                this.form
                    .post('http://localhost:8000/api/supermarchands')
                    .then(marchand => this.supermarchands.push(marchand));
            },
            onSearch(){
                 // Temp for this form only.
                this.form
                    .post('api/supermarchands')
                    .then(marchand => this.supermarchands.push(marchand));
            },
            getDepartements(){
                 // Temp for this form only.
                axios.get('http://localhost:8000/api/departements')
                .then(({data}) => this.departements = data.data);
            },

            getCommunes(departement){
                 // Temp for this form only.
                axios.get('http://localhost:8000/api/departements/'+departement+'/communes')
                .then(({data}) => this.communes = data.data);
            }
        }
    }
</script>