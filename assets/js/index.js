const keys = [
    {
        id: 1,
        name: '1'
    },
    {
        id: 2,
        name: '2'
    },
    {
        id: 3,
        name: '3'
    },
    {
        id: 4,
        name: '4'
    },
    {
        id: 5,
        name: '5'
    },
    {
        id: 6,
        name: '6'
    },
    {
        id: 7,
        name: '7'
    },
    {
        id: 8,
        name: '8'
    },
    {
        id: 9,
        name: '9'
    },
    {
        id: 10,
        name: '10'
    }
];


$(document).ready(function () {
    const user_id = getParameterByName('user');
    createHtmlElementForGrid();
    addUser();

    const fetchData = () => {
        $.ajax({
            type: 'GET',
            url: 'keyboardController.php',
            data: {user_id: user_id, action: 'fetch_data'},
            dataType: "json",
            success: function (res) {
                const id = user_id;
                const keys = res.data.keys
                $.each(keys, function (index, value) {
                    let color = value || "#ffffff";
                    $('#'+index).css('background-color', color);
                })

                $('#hidden').val(res.data.control);
                console.log(res);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        })
    }

    // Fetch data initially on page load
    fetchData();

    //Fetch data periodically every 5 seconds (adjust the interval as needed)
    setInterval(fetchData, 1000);
})


const addUser = () => {
    const user_id = getParameterByName('user');
    if (!user_id || user_id != 1 && user_id != 2) {
        alert(`User must be either 1 or 2`);
    }

    $.ajax({
        type: 'POST',
        url: 'keyboardController.php',
        data: {user_id: user_id, action: 'create_user'},
        success: function (res) {
            $('.key').addClass('user_'+user_id);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    })
}

const controlKeyboard = () => {
    const user_id = getParameterByName('user');

    $.ajax({
        type: 'POST',
        url: 'keyboardController.php',
        data: {user_id: user_id, control: 1, action: 'update_control'},
        success: function (res) {
            console.log(res);
            $(".key").attr("onClick", `controlKey(this.id)`);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    })

    releaseControlKey();
}

const releaseControlKey = () => {
    let eventIsClicked = false;
    $('.key').click(function () {
        eventIsClicked = true;
    })

    // Timer to check if the event is clicked within 120 seconds
    setTimeout(function() {
        if (!eventIsClicked) {
            $.ajax({
                type: 'POST',
                url: 'keyboardController.php',
                data: { control: 1, action: 'release_control'},
                success: function (res) {
                    console.log(res);
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            })
        }
    }, 120000);
}

const controlKey = (id) => {
    const user_id = getParameterByName('user');
    let gridColor = $('#'+id).val();
    let color = user_id == 1 ? 'red' : 'yellow';
    const controlValue = $('#hidden').val();
    if (controlValue == 1) {
        color = gridColor == 'red' || gridColor == 'yellow' ? 'white' : color;
        $('#'+id).css('background-color', color);
        $('#'+id).val(color);

        $.ajax({
            type: 'POST',
            url: 'keyboardController.php',
            data: {user_id: user_id, control: 0, key_name: id, key_value: color, action: 'update_key_status'},
            success: function (res) {
                console.log(res);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        })
    }
}
const createHtmlElementForGrid = () => {
    keys.map((item) => {
        let div = document.createElement('div');
        div.className = `key`;
        div.setAttribute('id', `key_${item.id}` );
        div.setAttribute('value', `white`);
        div.textContent = item.name;
        document.getElementById("keyboard").appendChild(div);
    })
}

const getParameterByName = (name) => {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}
