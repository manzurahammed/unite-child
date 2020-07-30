class a {

    constructor() {
        this.get_data.bind(this)
        this.find_my_book.bind(this)
    }

    get_data(){
        alert('Found data');
    }

    find_my_book(){
        this.get_data()
    }
}