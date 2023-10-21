<template>

	<div class="justify-content-center align-items-center mx-auto">

		<div class="modal modal--zoom-component" tabindex="-1" data-keyboard="true" aria-hidden="true" id="zoom-modal-component" data-backdrop="static">
			<div role="document">
				<div class="modal-content container my-4">
					<button type="button" class="close" aria-label="Close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
					</button>
					<div class="modal--zoom-component__img-wrapper d-flex align-items-center text-center p-5">
						<img :src="selectedImage" class="img-fluid d-block m-auto" alt="full sized image" />
					</div>
				</div>
			</div>
		</div>

		<div class="text-center position-relative py-0 h-100 justify-content-center align-items-center d-flex w-100 min-h-100px min-h-sm-420px">
			<div>
				<img 
                    :src="selectedImage"
                    class="img-fluid rounded product-img-responsive image--gallery--active max-h-300px max-h-sm-450px max-h-md-650px"
				>
			</div>
			<button type="button" title="click to zoom" class="btn btn-sm text-hover-darker link--gallery-zoom" @click.prevent="zoomImage()">
				<i class="fa fa-search"></i>
			</button>
		</div>

		<div class="mt-5 mt-lg-5 row d-flex justify-content-center" v-if="images.length > 0">
			<div class="col-12 text-center">
				<a v-for="image in images" 
					class="d-inline-block border rounded mb-3 product-small-image-container mx-3 align-items-center justify-content-center p-3" 
					@click="updateCurrentImage(image.src)"
				>
					<img 
						:src="image.src" 
						class="m-auto w-100 d-block w-auto h-auto"
						style="max-width: 35px; max-height: 35px;"
					>
				</a>
			</div>
		</div>

		
	</div>
	
</template>

<script>

	export default {

		props: [
			'product'
		],

		data(){
			return {
				selectedImage: '/storage/notfound.png'
			}
		},

		mounted() {
			try {
				this.selectedImage = '/storage/' + this.product.main_image;

				if (this.selectedImage == '/storage//storage/notfound.png') {
					this.selectedImage = '/storage/notfound.png'
				}

				this.selectedImage = this.selectedImage.replace('//', '/')

			} catch (e) {

			}
		},

		watch: {
			product(){
				try {
					this.selectedImage = '/storage/' + this.product.main_image
					this.selectedImage = this.selectedImage.replace('//', '/')
				} catch (e) {
					
				}
			}
		},

		methods: {

			updateCurrentImage(image){
				this.selectedImage = image

				if (this.selectedImage == '/storage//storage/notfound.png') {
					this.selectedImage = '/storage/notfound.png'
				}

				this.selectedImage = this.selectedImage.replace('//', '/')
			},

			zoomImage(){
				$('#zoom-modal-component').modal('show')
			}

		},

		computed: {
			images(){
				try {
					if(this.product.images && this.product.images.length > 0){
						return this.product.images
					}
				} catch (e) {

				}
				return [];
			}
		}
	}
	
</script>

<style lang="scss" scoped>
	
	.link--gallery-zoom {
		position: absolute;
		bottom: 0;
		right: 0;
		color: #BDBDBD;
		font-size: 1rem;
		z-index: 90;
	}

	.product-small-image-container{
		opacity: 0.8;
		cursor: pointer;
		transition: all .4s ease;
		&:hover{
			opacity: 1;
		}
	}
	
	.modal {
    &--zoom-component {
		.modal-content,
		&__img-wrapper {
			img {
				object-fit: contain;
				height: 100%;
			}
		}
		.close {
			width: 25px;
			height: 25px;
			border-radius: 50%;
			background: #f1f1f1;
			position: absolute;
			right: 1rem;
			top: 1rem;
			transition: all .4s ease;
			&:hover {
				background: #e5e5e5;
			}
		}
	}
}
</style>