const template = document.createElement('template')
template.innerHTML = `
	<style>
		slide-drawer{
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}
		#drawer {
            position: fixed;
            z-index: 9998;
            top: 0;
            bottom: 0;
            user-select: none;
		}
		#overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			z-index: 9991;
			//background: rgba(0,0,0,.5);
			opacity: 0;
			visibility: hidden;
		}
		.on {
			visibility: visible !important;
		}
		#grab {
            position: absolute;
            z-index: 9993;
            top: 0;
            height: 100vh;
            width: 20px;
		}
		.animate {
		  transition: all ease .25s;
		}
		.tkmm-toggle {
            width: 70px;
			height:100%;
            z-index: 9994;
            -webkit-transition: .5s ease-in-out;
            -moz-transition: .5s ease-in-out;
            -o-transition: .5s ease-in-out;
            transition: .5s ease-in-out;
            transition: right ease .25s;
            transition: left ease .25s;
            cursor: pointer;
			flex-direction:column;
			justify-content: center;
			align-items: stretch;
			align-items: center;
		}
		.tkmm-toggle:not(.close){
		}
		.tkmm-toggle span {
			margin:4px auto;
			right: 0;
            height: 2px;
			display:block;
            width: 30px;
			background: var(--menu_items_font_color, var(--all_font_color), black);
            border-radius: 2px;
            opacity: 1;
            left: 0;
            -webkit-transition: .25s ease-in-out;
            -moz-transition: .25s ease-in-out;
            -o-transition: .25s ease-in-out;
            transition: .25s ease-in-out;
		}
		.tkmm-toggle.left {
            right: -55px;
		}
		.tkmm-toggle.right {
           left: -55px;
		}
		.tkmm-toggle.leftOpen {
            right: 10px;
		}
		.tkmm-toggle.rightOpen {
            left: 10px;
		}

		.tkmm-toggle span:nth-child(1) {
            -webkit-transform-origin: center;
            -moz-transform-origin: center;
            -o-transform-origin: center;
            transform-origin: center;
		}

		.tkmm-toggle span:nth-child(2) {
            -webkit-transform-origin: left center;
            -moz-transform-origin: left center;
            -o-transform-origin: left center;
            transform-origin: left center;
		}

		.tkmm-toggle span:nth-child(3) {
            -webkit-transform-origin: center;
            -moz-transform-origin: center;
            -o-transform-origin: center;
            transform-origin: center;
		}

		.tkmm-toggle.close span:nth-child(1) {
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -o-transform: rotate(45deg);
            transform: rotate(45deg);
            top: 10px;
		}

		.tkmm-toggle.close span:nth-child(2) {
            width: 0%;
            opacity: 0;
		}

		.tkmm-toggle.close span:nth-child(3) {
            -webkit-transform: rotate(-45deg);
            -moz-transform: rotate(-45deg);
            -o-transform: rotate(-45deg);
            transform: rotate(-45deg);
            top: 10px;
		}




	</style>
	<div id="toggle" class="tkmm-toggle">
		<span></span>
		<span></span>
		<span></span>
	</div>
    <div id="drawer">
		<div id="grab"></div>
		<!--<div id="toggle_close" class="tkmm-toggle open close">
			<span></span>
			<span></span>
			<span></span>
		</div>-->
		<slot></slot>
	</div>
	<div id="overlay"></div>
`

class SlideDrawer extends HTMLElement {
    constructor() {
        super()
		
		const shadowRoot = this.attachShadow({ mode: "open" })
        shadowRoot.appendChild(template.content.cloneNode(true))
		this.overlay = shadowRoot.getElementById('overlay')
		this.grab = shadowRoot.getElementById('grab')
		this.drawer = shadowRoot.getElementById('drawer')
		this.toggles = shadowRoot.querySelectorAll('.tkmm-toggle')
		
		// Grab and set all options
		this.right = this.hasAttribute('right')
		this.drawer_position = 'left';
		if(this.right){this.drawer_position = 'right'};		
		this.overlayOpacity = this.getAttribute('overlayOpacity') || .5
		this.overlay.style.background = `rgba(0,0,0,${this.overlayOpacity})`
		this.mobileWidth = this.getAttribute('mobileWidth') || '100%'
		this.mobileBreak = +this.getAttribute('mobileBreak') || 1200
		this.toggleDisplay = (window.innerWidth <= this.mobileBreak) ? 'block' : 'none'
		this.drawer.style.width = window.innerWidth <= this.mobileBreak ? this.mobileWidth : this.getAttribute('width') || '30%'
		this.drawer.style.backgroundColor = this.getAttribute('bg') || 'white'
		this.distance
		
		// set side specific classes and settings after right option is checked
		
		if(this.right) {
			this.drawer.style.left = window.innerWidth + 'px'
			this.grab.style.left = '-20px'
			// this.toggle.classList.add('right')
		} else {
			this.drawer.style.left = -this.drawer.offsetWidth + 'px'
			this.grab.style.right = '-20px'
			// this.toggle.classList.add('left')
		}
		this.resizeId

		document.getElementById("menu-wrapper").style.display = "block";
    }
	
	// Add event listeners once web component mounts
	
	connectedCallback() {
		this.grab.addEventListener('mousedown', this.handleMouseDown)
		this.grab.addEventListener('touchstart', this.handleMouseDown)
		window.addEventListener('resize', this.handleResize)
		this.toggles.forEach(toggle => {
			toggle.addEventListener('click', this.toggleDrawer)
		})
		
		const items = Array.from(this.querySelectorAll('ul'))
		this.menuInit(items)
		
	}
	
	// handles window resize
	
	handleResize = e => {
		this.drawer.classList.remove('animate')
		this.toggles.forEach(toggle => {
			window.innerWidth < this.mobileBreak ? toggle.style.display = 'flex' : toggle.style.display = 'none'
		})
		
		if(this.right) {
			if(this.drawer.classList.contains('open')) {
				this.drawer.style.left = window.innerWidth - this.drawer.offsetWidth + 'px'
			} else {
				this.drawer.style.left = window.innerWidth + 'px'
			}
		} else {
			if(this.drawer.classList.contains('open')) {
				this.drawer.style.left = 0
			} else {
				this.drawer.style.left = -this.drawer.offsetWidth + 'px'
			}
		}
	}
	
	// handles mouse down and drag on drawer
	
	handleMouseDown = e => {
		// console.log(e);
		this.drawer.classList.remove('animate')
		this.overlay.classList.add('on')
		
		// moves drawer with mouse position during drag
		
		// let count_thing = 0;
		const moveAt = e => {
			
			let pageX = e.type == 'touch' ?  e.pageX : e.touches[0].clientX
			// count_thing++;
			// console.log('mousedown:' + count_thing)
				
				
				if(this.right) {
					if(pageX > window.innerWidth - this.drawer.offsetWidth && this.drawer.getBoundingClientRect().left <= window.innerWidth + 10) {
						this.drawer.style.left = pageX + 'px'
						// console.log(pageX)
					}
				} else {
					if(pageX < this.drawer.offsetWidth && this.drawer.getBoundingClientRect().right >= -10) {
						this.drawer.style.left = pageX - this.drawer.offsetWidth + 'px'
					}
				}
		}
		
		// checks current position of drawer converts to percentage completed and sets overlay opacity as such
		
		const overlayPercentage = e => {
			let pageX = e.type == 'touch' ? e.pageX : e.touches[0].clientX
			
			if(this.right) {
				let percentage = 1 - ((pageX - (window.innerWidth - this.drawer.offsetWidth)) / this.drawer.offsetWidth)
				if(pageX > window.innerWidth - this.drawer.offsetWidth) {
					this.overlay.style.opacity = percentage
				}
			} else {
				let percentage = pageX / this.drawer.offsetWidth
				if(pageX < this.drawer.offsetWidth) {
					this.overlay.style.opacity = percentage
				}
			}
		}

		moveAt(e)
		
		// calls both overlay and drawer move functions when mouse is moved

		const onMouseMove = e => {
			moveAt(e)
			overlayPercentage(e)
		}
		
		// event listener added on mouse down for dragging
		
		this.grab.addEventListener('mousemove', onMouseMove)
		this.grab.addEventListener('touchmove', onMouseMove)

		// on mouse up checks current drawer position for open/close threshold and kills mouse move listener
		
		this.grab.onmouseup = () => {
			this.grab.removeEventListener('mousemove', onMouseMove)
			this.grab.onmouseup = null
			if(this.right) {
				this.drawer.getBoundingClientRect().left < window.innerWidth - (this.drawer.offsetWidth / 4) ? 
				this.open() : this.close()
			} else {
				this.drawer.getBoundingClientRect().right > this.drawer.offsetWidth / 4 ? 
				this.open() : this.close()
			}
		}
		
		this.grab.ontouchend = () => {
			this.grab.removeEventListener('mousemove', onMouseMove)
			this.grab.onmouseup = null
			if(this.right) {
				this.drawer.getBoundingClientRect().left < window.innerWidth - (this.drawer.offsetWidth / 4) ? 
				this.open() : this.close()
			} else {
				this.drawer.getBoundingClientRect().right > this.drawer.offsetWidth / 4 ? 
				this.open() : this.close()
			}
		}
		
		// disables a built in function that isn't neccessary and can cause issues 
		
		this.grab.ondragstart = () => {
  			return false
		}

	}
	
	// checks if drawer is open/closed when the menu button is clicked and toggles it
	
	toggleDrawer = () => {
		this.drawer.classList.add('animate')
		if(this.right) {
			this.drawer.getBoundingClientRect().right == window.innerWidth ?
			this.close() : this.open()
		} else {
			this.drawer.getBoundingClientRect().left == 0 ?
			this.close() : this.open()
		}
	}
	
	// adds all classes to open drawer and overlay, adds listener to overlay for closing with outside drawer click
	
	open = () => {
		document.body.style.overflow = 'hidden'
		this.drawer.classList.add('animate')
		this.drawer.classList.add('open')
		// this.right ? this.toggle.classList.add('rightOpen') : this.toggle.classList.add('leftOpen')
		// this.toggle.classList.add('open')
		this.overlay.classList.add('on', 'animate')
		this.right ? 
			this.drawer.style.left = window.innerWidth - this.drawer.offsetWidth + 'px' : this.drawer.style.left = 0
		this.overlay.style.opacity = 1
		

		this.overlay.addEventListener('mousedown', this.handleOpenMouseDown)
		this.overlay.addEventListener('touchstart', this.handleOpenMouseDown)
		
	}
	
	// removes all classes to close drawer and overlay, sets drawer back to closed position
	
	close = () => {
		this.drawer.classList.add('animate')
		this.drawer.classList.remove('open')
		// this.right ? this.toggle.classList.remove('rightOpen') : this.toggle.classList.remove('leftOpen')
		// this.toggle.classList.remove('open')
		this.overlay.style.opacity = 0
		this.overlay.classList.remove('on', 'animate')
		this.right ? 
			this.drawer.style.left = window.innerWidth + 'px' : this.drawer.style.left = -this.drawer.offsetWidth + 'px'
		document.body.style.overflow = 'initial'
	}
	
	// handles outside drawer click then kills listener
	
	handleOpenClick = e => {
		if(e.target == this.overlay) {
			let x1 = e.clientX, x2 = this.drawer.getBoundingClientRect().left
			console.log(x2-x1)
			this.close()
			this.overlay.removeEventListener('click', this.handleOpenClick)
		}
	}
	
	handleOpenMouseDown = e => {
		
		const handleOpenMove = e => {
			let pageX = e.type == 'touch' ? e.pageX : e.touches[0].clientX
			
			if(this.right) {
				if(pageX + this.distance > window.innerWidth - this.drawer.offsetWidth && this.drawer.getBoundingClientRect().left <= window.innerWidth + 10) {
					this.drawer.style.left = pageX + this.distance + 'px'
				}
			} else {
				if(pageX - this.distance < this.drawer.offsetWidth && this.drawer.getBoundingClientRect().right >= -10) {
					this.drawer.style.left = (pageX - this.distance) - this.drawer.offsetWidth + 'px'
				}
			}
			
		}
		
		const overlayPercentage = e => {
			let pageX = e.type == 'touch' ? e.pageX : e.touches[0].clientX
			
			if(this.right) {
				let percentage = 1 - (((pageX + this.distance ) - (window.innerWidth - this.drawer.offsetWidth)) / this.drawer.offsetWidth)
				if((pageX + this.distance) > window.innerWidth - this.drawer.offsetWidth) {
					this.overlay.style.opacity = percentage
				}
			} else {
				let percentage = (pageX - this.distance) / this.drawer.offsetWidth
				if((pageX - this.distance) < this.drawer.offsetWidth) {
					this.overlay.style.opacity = percentage
				}
			}
		}
		
		const onMove = e => {
			handleOpenMove(e)
			overlayPercentage(e)
		}
		
		if(e.target == this.overlay) {
			let x1 = e.pageX, x2 = this.right ? 
				this.drawer.getBoundingClientRect().left : this.drawer.offsetWidth
			this.distance = this.right ? x2 - x1 : x1 - x2
			this.drawer.classList.remove('animate')
			
			this.grab.addEventListener('mousemove', onMove)
			this.grab.addEventListener('touchmove', onMove)
			
			this.overlay.removeEventListener('mousedown', this.handleOpenMouseDown)
			this.overlay.removeEventListener('touchstart', this.handleOpenMouseDown)
		}
		
		document.onmouseup = () => {
			document.removeEventListener('mousemove', onMove)
			document.onmouseup = null
			this.close()
		}
		
		document.ontouchend = () => {
			document.removeEventListener('touchmove', onMove)
			document.ontouchend = null
			this.close()
		}
		
	}
	
	menuInit = items => {
		let drawer_position = this.drawer_position
		this.toggles.forEach(toggle => {
			window.innerWidth < this.mobileBreak ? toggle.style.display = 'flex' : toggle.style.display = 'none'
		})
		items.forEach(item => {
			item.style.width = this.drawer.offsetWidth + 'px'
			if (item.parentNode.tagName != "DIV") item.style.position = 'absolute'
			if (item.parentNode.tagName == "LI") {
				let dropdownArrow = '<span class="fas fa-caret-down"></span>'
				item.parentNode.classList.contains('menu-item-has-children') 
					? item.parentNode.firstChild.innerHTML += dropdownArrow
					: null

				let back = document.createElement('li')
				let home = document.createElement('li')
				let title = document.createElement('li')
				home.innerHTML = '<span class="fas fa-angle-double-left"></span> Go Home' + home.innerHTML
				back.innerHTML = '<span class="fas fa-angle-left"></span> Back' + back.innerHTML
				item.style[drawer_position] = -item.offsetWidth + 'px'
				item.style.top = 0
				item.prepend(back)
				item.prepend(home)
				item.prepend(title)
				back.style.position = 'absolute'
				back.style.top = '80px'
				back.style.right = '20px'
				back.style.fontSize = '14px'
				home.style.position = 'absolute'
				home.style.top = '80px'
				home.style.left = '20px'
				home.style.fontSize = '14px'
				title.style.position = 'absolute'
				title.style.top = '30px'
				title.style.left = '50%'
				title.style.fontSize = '14px'
				title.style.fontWeight = 'bold'
				title.style.transform = 'translateX(-50%)'
				item.parentNode.firstChild.onclick = (e) => {
					e.preventDefault()
                    item.style[drawer_position] = 0
					item.parentNode.style.position = 'absolute'
					item.parentNode.style.top = '0'
					item.parentNode.style.bottom = '0'
					item.parentNode.style.left = '0'
					item.parentNode.style[drawer_position] = '0'
					title.innerText = item.parentNode.firstChild.innerText.replace('>', '')
				}
				back.onclick = () => {
					item.parentNode.style.position = 'initial'
					item.style[drawer_position] = -item.offsetWidth + 'px'
					item.parentNode.style.top = 'initial'
					item.parentNode.style.bottom = 'initial'
					item.parentNode.style.left = 'initial'
					item.parentNode.style[drawer_position] = 'initial'
				}
				home.onclick = () => {
					items.forEach(item => {
						if(item.parentNode.tagName == "LI") {
							item.parentNode.style.position = 'initial'
							item.style[drawer_position] = -item.offsetWidth + 'px'
							item.parentNode.style.top = 'initial'
							item.parentNode.style.bottom = 'initial'
							item.parentNode.style.left = 'initial'
							item.parentNode.style[drawer_position] = 'initial'
						}
					})
				}
			}
		})
	}
}

window.customElements.define('slide-drawer', SlideDrawer)

// get the sticky element
const stickyElm = document.querySelector('header#masthead')

// if the header is set to sticky...
if(stickyElm.classList.contains('sticky_header')){

	// calculate the height of the header items and position them accordingly
	let wp_admin_bar = document.querySelector('#wpadminbar');
	let top_bar = document.querySelector('.top-bar-content');
	let header = document.querySelector('#masthead');
	let wp_admin_bar_height = 0;
	let top_bar_height = 0;
	let header_height = 0;

	
	// if the admin bar exists, grab that height
	if(document.body.classList.contains( 'logged-in' )){
		// set the wp_admin_bar height static values (because it's not immediately available in the DOM to select and get the dynamic value -- it'll probably not change in height frequently anyway...)
		if(window.innerWidth > 784 ){
			wp_admin_bar_height = 32;
		} else {
			wp_admin_bar_height = 46;
		}
	}
	
	// if the theme top bar exists, grab that height
	if(top_bar){
		top_bar_height = document.querySelector('.top-bar-content').offsetHeight;
	}

	// the header always exists, grab that height
	header_height = document.querySelector('#masthead').offsetHeight;

	// position top bar
	// top bar is positioned fine, due to html margin-top from wp

	// position header
	header.style.position =  "absolute";
	header.style.top = (wp_admin_bar_height+top_bar_height) + "px";


	// find half value
	document.addEventListener('scroll', function(e){
	
	if(wp_admin_bar_height || top_bar_height){

		// top bar is present, move header below it and snap it to the top of the browser after scrolling past the top bar content
		if(window.scrollY > top_bar_height){
			header.style.position = "fixed";
			header.style.top = wp_admin_bar_height + "px";
		}

		if(window.scrollY < top_bar_height){
			header.style.position =  "absolute";
			header.style.top = (wp_admin_bar_height+top_bar_height) + "px";
		}

		// no top bar is present, behave normally
		if(window.scrollY > (header_height+top_bar_height)/2){
			stickyElm.classList.add('is-stuck');
		}
		if(window.scrollY < (header_height+top_bar_height)/4){
			stickyElm.classList.remove('is-stuck');
		}

	} else {

		// no top bar is present, behave normally
		if(window.scrollY > header_height/2){
			header.style.position = "fixed";
			header.style.top = wp_admin_bar_height + "px";
			stickyElm.classList.add('is-stuck');
		}
		if(window.scrollY < header_height/4){
			stickyElm.classList.remove('is-stuck');
			header.style.position =  "absolute";
			header.style.top = (wp_admin_bar_height+top_bar_height) + "px";

		}
	}


});
}

jQuery(document).ready(function($) { 
	$('.navbar .dropdown').hover(function() {
		$(this).find('.dropdown-menu').first().stop().fadeIn(200); // use slideDown(300) for animation
	}, function() {
		// $(this).find('.dropdown-menu').first().stop().fadeOut(100) // use slideUp(300) for animation
		$(this).find('.dropdown-menu').first().hide() // use slideUp(300) for animation
	});
});