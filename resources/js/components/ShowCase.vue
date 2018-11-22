<template>
    <div class="row">
        <div class="col-md-4" v-for="product in products">
            <div class="card mb-4 shadow-sm">
                <img class="card-img-top"
                     style="width: 100%; display: block; padding: 20px"
                     :src="product.image" data-holder-rendered="true" />

                <div class="card-body">
                    <div class="badge badge-danger" style="font-size: 16px;">
                        R$ {{product.price}}
                    </div>

                    <div class="badge badge-success" style="font-size: 16px;">
                        R$ {{product.sale}}
                    </div>

                    <hr>

                    <p class="card-text">
                        <a v-if="!product.name" :href="product.url" target='_blank'>
                            {{product.url}}
                        </a>
                        <span v-else>
                            {{product.name}}
                        </span>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a :href="'/produto/'+product.id+'/visualizar'" class="btn btn-sm btn-outline-info">
                                Visualizar
                            </a>
                            <a href="" class="btn btn-sm {!! $product->status === 0 ? 'btn-outline-success' : 'btn-outline-secondary' !!}">
                                {{product.status === 0 ? 'Ativar' : 'Desativar'}}
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger">
                                Remover
                            </button>
                        </div>
                        <small class="text-muted">
                            {{ product.times_read }} {{ product.times_read > 1 ? 'verificações' : (product.times_read === 0 ? 'verificações' : 'verificação') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "show-case",
        data () {
            return {
                products: []
            }
        },
        methods: {
            loadProducts () {
                axios.get('ajax/products')
                    .then(r => {
                        this.products = r.data;
                    })
            }
        },
        created () {
            this.loadProducts();
            setInterval(this.loadProducts, 60 * 1000);
        }
    }
</script>

<style scoped>

</style>