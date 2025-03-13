<html lang="en"><script>
    window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
    constructor(name, destination) {
        this.name = name;
        this.destination = destination;
        this.serverListeners = {};
        this.bgRequestsListeners = {};
        this.bgEventsListeners = {};
        window.addEventListener('message', (message) => {
            const data = message.data;
            const isNotForMe = !(data.destination && data.destination === this.name);
            const hasNotEventProp = !data.event;
            if (isNotForMe || hasNotEventProp) {
                return;
            }
            if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                const response = data.args;
                if (this.hasBgRequestListener(response.requestId)) {
                    try {
                        this.bgRequestsListeners[response.requestId](response.response);
                    }
                    catch (e) {
                        console.log(e);
                    }
                    delete this.bgRequestsListeners[response.requestId];
                }
            }
            else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                const response = data.args;
                if (this.hasBgEventListener(response.event)) {
                    try {
                        this.bgEventsListeners[data.id](response.payload);
                    }
                    catch (e) {
                        console.log(e);
                    }
                }
            }
            else if (this.hasServerListener(data.event)) {
                try {
                    this.serverListeners[data.event](data.args);
                }
                catch (e) {
                    console.log(e);
                }
            }
            else {
                console.log(`event not handled: ${data.event}`);
            }
        });
    }
    emitToServer(event, args) {
        const id = this.generateUIID();
        const message = {
            args,
            destination: this.destination,
            event,
            id,
        };
        window.postMessage(message, location.origin);
        return id;
    }
    emitToBg(bgEventName, args) {
        const requestId = this.generateUIID();
        const request = { bgEventName, requestId, args };
        this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
        return requestId;
    }
    hasServerListener(event) {
        return !!this.serverListeners[event];
    }
    hasBgRequestListener(requestId) {
        return !!this.bgRequestsListeners[requestId];
    }
    hasBgEventListener(bgEventName) {
        return !!this.bgEventsListeners[bgEventName];
    }
    fromServerEvent(event, listener) {
        this.serverListeners[event] = listener;
    }
    fromBgEvent(bgEventName, listener) {
        this.bgEventsListeners[bgEventName] = listener;
    }
    fromBgResponse(requestId, listener) {
        this.bgRequestsListeners[requestId] = listener;
    }
    generateUIID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')</script><script>
    const hideMyLocation = new (class HideMyLocation {
    constructor(clientKey) {
        this.clientKey = clientKey;
        this.watchIDs = {};
        this.client = window[Symbol.for(clientKey)];
        const getCurrentPosition = navigator.geolocation.getCurrentPosition;
        const watchPosition = navigator.geolocation.watchPosition;
        const clearWatch = navigator.geolocation.clearWatch;
        const self = this;
        navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
            self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
        };
        navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
            return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
        };
        navigator.geolocation.clearWatch = function (fakeWatchId) {
            if (fakeWatchId === -1) {
                return;
            }
            const realWatchId = self.watchIDs[fakeWatchId];
            delete self.watchIDs[fakeWatchId];
            return clearWatch.apply(this, [realWatchId]);
        };
    }
    handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
        const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
        let fakeWatchId = this.getRandomInt(0, 100000);
        this.client.fromBgResponse(requestId, (response) => {
            if (response.enabled) {
                if (response.status === 'SUCCESS') {
                    const position = this.map(response);
                    successCallback(position);
                }
                else {
                    const error = this.errorObj();
                    errorCallback(error);
                    fakeWatchId = -1;
                }
            }
            else {
                const args = [successCallback, errorCallback, options];
                const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                if (type === 'WATCH') {
                    this.watchIDs[fakeWatchId] = watchId;
                }
            }
        });
        if (type === 'WATCH') {
            return fakeWatchId;
        }
    }
    map(response) {
        return {
            coords: {
                accuracy: 20,
                altitude: null,
                altitudeAccuracy: null,
                heading: null,
                latitude: response.latitude,
                longitude: response.longitude,
                speed: null,
            },
            timestamp: Date.now(),
        };
    }
    errorObj() {
        return {
            code: 1,
            message: 'User denied Geolocation',
        };
    }
    getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
  </script><head><script>
    window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
    constructor(name, destination) {
        this.name = name;
        this.destination = destination;
        this.serverListeners = {};
        this.bgRequestsListeners = {};
        this.bgEventsListeners = {};
        window.addEventListener('message', (message) => {
            const data = message.data;
            const isNotForMe = !(data.destination && data.destination === this.name);
            const hasNotEventProp = !data.event;
            if (isNotForMe || hasNotEventProp) {
                return;
            }
            if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                const response = data.args;
                if (this.hasBgRequestListener(response.requestId)) {
                    try {
                        this.bgRequestsListeners[response.requestId](response.response);
                    }
                    catch (e) {
                        console.log(e);
                    }
                    delete this.bgRequestsListeners[response.requestId];
                }
            }
            else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                const response = data.args;
                if (this.hasBgEventListener(response.event)) {
                    try {
                        this.bgEventsListeners[data.id](response.payload);
                    }
                    catch (e) {
                        console.log(e);
                    }
                }
            }
            else if (this.hasServerListener(data.event)) {
                try {
                    this.serverListeners[data.event](data.args);
                }
                catch (e) {
                    console.log(e);
                }
            }
            else {
                console.log(`event not handled: ${data.event}`);
            }
        });
    }
    emitToServer(event, args) {
        const id = this.generateUIID();
        const message = {
            args,
            destination: this.destination,
            event,
            id,
        };
        window.postMessage(message, location.origin);
        return id;
    }
    emitToBg(bgEventName, args) {
        const requestId = this.generateUIID();
        const request = { bgEventName, requestId, args };
        this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
        return requestId;
    }
    hasServerListener(event) {
        return !!this.serverListeners[event];
    }
    hasBgRequestListener(requestId) {
        return !!this.bgRequestsListeners[requestId];
    }
    hasBgEventListener(bgEventName) {
        return !!this.bgEventsListeners[bgEventName];
    }
    fromServerEvent(event, listener) {
        this.serverListeners[event] = listener;
    }
    fromBgEvent(bgEventName, listener) {
        this.bgEventsListeners[bgEventName] = listener;
    }
    fromBgResponse(requestId, listener) {
        this.bgRequestsListeners[requestId] = listener;
    }
    generateUIID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')</script><script>
    const hideMyLocation = new (class HideMyLocation {
    constructor(clientKey) {
        this.clientKey = clientKey;
        this.watchIDs = {};
        this.client = window[Symbol.for(clientKey)];
        const getCurrentPosition = navigator.geolocation.getCurrentPosition;
        const watchPosition = navigator.geolocation.watchPosition;
        const clearWatch = navigator.geolocation.clearWatch;
        const self = this;
        navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
            self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
        };
        navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
            return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
        };
        navigator.geolocation.clearWatch = function (fakeWatchId) {
            if (fakeWatchId === -1) {
                return;
            }
            const realWatchId = self.watchIDs[fakeWatchId];
            delete self.watchIDs[fakeWatchId];
            return clearWatch.apply(this, [realWatchId]);
        };
    }
    handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
        const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
        let fakeWatchId = this.getRandomInt(0, 100000);
        this.client.fromBgResponse(requestId, (response) => {
            if (response.enabled) {
                if (response.status === 'SUCCESS') {
                    const position = this.map(response);
                    successCallback(position);
                }
                else {
                    const error = this.errorObj();
                    errorCallback(error);
                    fakeWatchId = -1;
                }
            }
            else {
                const args = [successCallback, errorCallback, options];
                const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                if (type === 'WATCH') {
                    this.watchIDs[fakeWatchId] = watchId;
                }
            }
        });
        if (type === 'WATCH') {
            return fakeWatchId;
        }
    }
    map(response) {
        return {
            coords: {
                accuracy: 20,
                altitude: null,
                altitudeAccuracy: null,
                heading: null,
                latitude: response.latitude,
                longitude: response.longitude,
                speed: null,
            },
            timestamp: Date.now(),
        };
    }
    errorObj() {
        return {
            code: 1,
            message: 'User denied Geolocation',
        };
    }
    getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
  </script><script>
    window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
    constructor(name, destination) {
        this.name = name;
        this.destination = destination;
        this.serverListeners = {};
        this.bgRequestsListeners = {};
        this.bgEventsListeners = {};
        window.addEventListener('message', (message) => {
            const data = message.data;
            const isNotForMe = !(data.destination && data.destination === this.name);
            const hasNotEventProp = !data.event;
            if (isNotForMe || hasNotEventProp) {
                return;
            }
            if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                const response = data.args;
                if (this.hasBgRequestListener(response.requestId)) {
                    try {
                        this.bgRequestsListeners[response.requestId](response.response);
                    }
                    catch (e) {
                        console.log(e);
                    }
                    delete this.bgRequestsListeners[response.requestId];
                }
            }
            else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                const response = data.args;
                if (this.hasBgEventListener(response.event)) {
                    try {
                        this.bgEventsListeners[data.id](response.payload);
                    }
                    catch (e) {
                        console.log(e);
                    }
                }
            }
            else if (this.hasServerListener(data.event)) {
                try {
                    this.serverListeners[data.event](data.args);
                }
                catch (e) {
                    console.log(e);
                }
            }
            else {
                console.log(`event not handled: ${data.event}`);
            }
        });
    }
    emitToServer(event, args) {
        const id = this.generateUIID();
        const message = {
            args,
            destination: this.destination,
            event,
            id,
        };
        window.postMessage(message, location.origin);
        return id;
    }
    emitToBg(bgEventName, args) {
        const requestId = this.generateUIID();
        const request = { bgEventName, requestId, args };
        this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
        return requestId;
    }
    hasServerListener(event) {
        return !!this.serverListeners[event];
    }
    hasBgRequestListener(requestId) {
        return !!this.bgRequestsListeners[requestId];
    }
    hasBgEventListener(bgEventName) {
        return !!this.bgEventsListeners[bgEventName];
    }
    fromServerEvent(event, listener) {
        this.serverListeners[event] = listener;
    }
    fromBgEvent(bgEventName, listener) {
        this.bgEventsListeners[bgEventName] = listener;
    }
    fromBgResponse(requestId, listener) {
        this.bgRequestsListeners[requestId] = listener;
    }
    generateUIID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')</script><script>
    const hideMyLocation = new (class HideMyLocation {
    constructor(clientKey) {
        this.clientKey = clientKey;
        this.watchIDs = {};
        this.client = window[Symbol.for(clientKey)];
        const getCurrentPosition = navigator.geolocation.getCurrentPosition;
        const watchPosition = navigator.geolocation.watchPosition;
        const clearWatch = navigator.geolocation.clearWatch;
        const self = this;
        navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
            self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
        };
        navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
            return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
        };
        navigator.geolocation.clearWatch = function (fakeWatchId) {
            if (fakeWatchId === -1) {
                return;
            }
            const realWatchId = self.watchIDs[fakeWatchId];
            delete self.watchIDs[fakeWatchId];
            return clearWatch.apply(this, [realWatchId]);
        };
    }
    handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
        const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
        let fakeWatchId = this.getRandomInt(0, 100000);
        this.client.fromBgResponse(requestId, (response) => {
            if (response.enabled) {
                if (response.status === 'SUCCESS') {
                    const position = this.map(response);
                    successCallback(position);
                }
                else {
                    const error = this.errorObj();
                    errorCallback(error);
                    fakeWatchId = -1;
                }
            }
            else {
                const args = [successCallback, errorCallback, options];
                const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                if (type === 'WATCH') {
                    this.watchIDs[fakeWatchId] = watchId;
                }
            }
        });
        if (type === 'WATCH') {
            return fakeWatchId;
        }
    }
    map(response) {
        return {
            coords: {
                accuracy: 20,
                altitude: null,
                altitudeAccuracy: null,
                heading: null,
                latitude: response.latitude,
                longitude: response.longitude,
                speed: null,
            },
            timestamp: Date.now(),
        };
    }
    errorObj() {
        return {
            code: 1,
            message: 'User denied Geolocation',
        };
    }
    getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
  </script><script>
    window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
    constructor(name, destination) {
        this.name = name;
        this.destination = destination;
        this.serverListeners = {};
        this.bgRequestsListeners = {};
        this.bgEventsListeners = {};
        window.addEventListener('message', (message) => {
            const data = message.data;
            const isNotForMe = !(data.destination && data.destination === this.name);
            const hasNotEventProp = !data.event;
            if (isNotForMe || hasNotEventProp) {
                return;
            }
            if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                const response = data.args;
                if (this.hasBgRequestListener(response.requestId)) {
                    try {
                        this.bgRequestsListeners[response.requestId](response.response);
                    }
                    catch (e) {
                        console.log(e);
                    }
                    delete this.bgRequestsListeners[response.requestId];
                }
            }
            else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                const response = data.args;
                if (this.hasBgEventListener(response.event)) {
                    try {
                        this.bgEventsListeners[data.id](response.payload);
                    }
                    catch (e) {
                        console.log(e);
                    }
                }
            }
            else if (this.hasServerListener(data.event)) {
                try {
                    this.serverListeners[data.event](data.args);
                }
                catch (e) {
                    console.log(e);
                }
            }
            else {
                console.log(`event not handled: ${data.event}`);
            }
        });
    }
    emitToServer(event, args) {
        const id = this.generateUIID();
        const message = {
            args,
            destination: this.destination,
            event,
            id,
        };
        window.postMessage(message, location.origin);
        return id;
    }
    emitToBg(bgEventName, args) {
        const requestId = this.generateUIID();
        const request = { bgEventName, requestId, args };
        this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
        return requestId;
    }
    hasServerListener(event) {
        return !!this.serverListeners[event];
    }
    hasBgRequestListener(requestId) {
        return !!this.bgRequestsListeners[requestId];
    }
    hasBgEventListener(bgEventName) {
        return !!this.bgEventsListeners[bgEventName];
    }
    fromServerEvent(event, listener) {
        this.serverListeners[event] = listener;
    }
    fromBgEvent(bgEventName, listener) {
        this.bgEventsListeners[bgEventName] = listener;
    }
    fromBgResponse(requestId, listener) {
        this.bgRequestsListeners[requestId] = listener;
    }
    generateUIID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')</script><script>
    const hideMyLocation = new (class HideMyLocation {
    constructor(clientKey) {
        this.clientKey = clientKey;
        this.watchIDs = {};
        this.client = window[Symbol.for(clientKey)];
        const getCurrentPosition = navigator.geolocation.getCurrentPosition;
        const watchPosition = navigator.geolocation.watchPosition;
        const clearWatch = navigator.geolocation.clearWatch;
        const self = this;
        navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
            self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
        };
        navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
            return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
        };
        navigator.geolocation.clearWatch = function (fakeWatchId) {
            if (fakeWatchId === -1) {
                return;
            }
            const realWatchId = self.watchIDs[fakeWatchId];
            delete self.watchIDs[fakeWatchId];
            return clearWatch.apply(this, [realWatchId]);
        };
    }
    handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
        const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
        let fakeWatchId = this.getRandomInt(0, 100000);
        this.client.fromBgResponse(requestId, (response) => {
            if (response.enabled) {
                if (response.status === 'SUCCESS') {
                    const position = this.map(response);
                    successCallback(position);
                }
                else {
                    const error = this.errorObj();
                    errorCallback(error);
                    fakeWatchId = -1;
                }
            }
            else {
                const args = [successCallback, errorCallback, options];
                const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                if (type === 'WATCH') {
                    this.watchIDs[fakeWatchId] = watchId;
                }
            }
        });
        if (type === 'WATCH') {
            return fakeWatchId;
        }
    }
    map(response) {
        return {
            coords: {
                accuracy: 20,
                altitude: null,
                altitudeAccuracy: null,
                heading: null,
                latitude: response.latitude,
                longitude: response.longitude,
                speed: null,
            },
            timestamp: Date.now(),
        };
    }
    errorObj() {
        return {
            code: 1,
            message: 'User denied Geolocation',
        };
    }
    getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
  </script><script>
    window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
    constructor(name, destination) {
        this.name = name;
        this.destination = destination;
        this.serverListeners = {};
        this.bgRequestsListeners = {};
        this.bgEventsListeners = {};
        window.addEventListener('message', (message) => {
            const data = message.data;
            const isNotForMe = !(data.destination && data.destination === this.name);
            const hasNotEventProp = !data.event;
            if (isNotForMe || hasNotEventProp) {
                return;
            }
            if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                const response = data.args;
                if (this.hasBgRequestListener(response.requestId)) {
                    try {
                        this.bgRequestsListeners[response.requestId](response.response);
                    }
                    catch (e) {
                        console.log(e);
                    }
                    delete this.bgRequestsListeners[response.requestId];
                }
            }
            else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                const response = data.args;
                if (this.hasBgEventListener(response.event)) {
                    try {
                        this.bgEventsListeners[data.id](response.payload);
                    }
                    catch (e) {
                        console.log(e);
                    }
                }
            }
            else if (this.hasServerListener(data.event)) {
                try {
                    this.serverListeners[data.event](data.args);
                }
                catch (e) {
                    console.log(e);
                }
            }
            else {
                console.log(`event not handled: ${data.event}`);
            }
        });
    }
    emitToServer(event, args) {
        const id = this.generateUIID();
        const message = {
            args,
            destination: this.destination,
            event,
            id,
        };
        window.postMessage(message, location.origin);
        return id;
    }
    emitToBg(bgEventName, args) {
        const requestId = this.generateUIID();
        const request = { bgEventName, requestId, args };
        this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
        return requestId;
    }
    hasServerListener(event) {
        return !!this.serverListeners[event];
    }
    hasBgRequestListener(requestId) {
        return !!this.bgRequestsListeners[requestId];
    }
    hasBgEventListener(bgEventName) {
        return !!this.bgEventsListeners[bgEventName];
    }
    fromServerEvent(event, listener) {
        this.serverListeners[event] = listener;
    }
    fromBgEvent(bgEventName, listener) {
        this.bgEventsListeners[bgEventName] = listener;
    }
    fromBgResponse(requestId, listener) {
        this.bgRequestsListeners[requestId] = listener;
    }
    generateUIID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')</script><script>
    const hideMyLocation = new (class HideMyLocation {
    constructor(clientKey) {
        this.clientKey = clientKey;
        this.watchIDs = {};
        this.client = window[Symbol.for(clientKey)];
        const getCurrentPosition = navigator.geolocation.getCurrentPosition;
        const watchPosition = navigator.geolocation.watchPosition;
        const clearWatch = navigator.geolocation.clearWatch;
        const self = this;
        navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
            self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
        };
        navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
            return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
        };
        navigator.geolocation.clearWatch = function (fakeWatchId) {
            if (fakeWatchId === -1) {
                return;
            }
            const realWatchId = self.watchIDs[fakeWatchId];
            delete self.watchIDs[fakeWatchId];
            return clearWatch.apply(this, [realWatchId]);
        };
    }
    handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
        const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
        let fakeWatchId = this.getRandomInt(0, 100000);
        this.client.fromBgResponse(requestId, (response) => {
            if (response.enabled) {
                if (response.status === 'SUCCESS') {
                    const position = this.map(response);
                    successCallback(position);
                }
                else {
                    const error = this.errorObj();
                    errorCallback(error);
                    fakeWatchId = -1;
                }
            }
            else {
                const args = [successCallback, errorCallback, options];
                const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                if (type === 'WATCH') {
                    this.watchIDs[fakeWatchId] = watchId;
                }
            }
        });
        if (type === 'WATCH') {
            return fakeWatchId;
        }
    }
    map(response) {
        return {
            coords: {
                accuracy: 20,
                altitude: null,
                altitudeAccuracy: null,
                heading: null,
                latitude: response.latitude,
                longitude: response.longitude,
                speed: null,
            },
            timestamp: Date.now(),
        };
    }
    errorObj() {
        return {
            code: 1,
            message: 'User denied Geolocation',
        };
    }
    getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
  </script><script>
      window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
      constructor(name, destination) {
          this.name = name;
          this.destination = destination;
          this.serverListeners = {};
          this.bgRequestsListeners = {};
          this.bgEventsListeners = {};
          window.addEventListener('message', (message) => {
              const data = message.data;
              const isNotForMe = !(data.destination && data.destination === this.name);
              const hasNotEventProp = !data.event;
              if (isNotForMe || hasNotEventProp) {
                  return;
              }
              if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                  const response = data.args;
                  if (this.hasBgRequestListener(response.requestId)) {
                      try {
                          this.bgRequestsListeners[response.requestId](response.response);
                      }
                      catch (e) {
                          console.log(e);
                      }
                      delete this.bgRequestsListeners[response.requestId];
                  }
              }
              else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                  const response = data.args;
                  if (this.hasBgEventListener(response.event)) {
                      try {
                          this.bgEventsListeners[data.id](response.payload);
                      }
                      catch (e) {
                          console.log(e);
                      }
                  }
              }
              else if (this.hasServerListener(data.event)) {
                  try {
                      this.serverListeners[data.event](data.args);
                  }
                  catch (e) {
                      console.log(e);
                  }
              }
              else {
                  console.log(`event not handled: ${data.event}`);
              }
          });
      }
      emitToServer(event, args) {
          const id = this.generateUIID();
          const message = {
              args,
              destination: this.destination,
              event,
              id,
          };
          window.postMessage(message, location.origin);
          return id;
      }
      emitToBg(bgEventName, args) {
          const requestId = this.generateUIID();
          const request = { bgEventName, requestId, args };
          this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
          return requestId;
      }
      hasServerListener(event) {
          return !!this.serverListeners[event];
      }
      hasBgRequestListener(requestId) {
          return !!this.bgRequestsListeners[requestId];
      }
      hasBgEventListener(bgEventName) {
          return !!this.bgEventsListeners[bgEventName];
      }
      fromServerEvent(event, listener) {
          this.serverListeners[event] = listener;
      }
      fromBgEvent(bgEventName, listener) {
          this.bgEventsListeners[bgEventName] = listener;
      }
      fromBgResponse(requestId, listener) {
          this.bgRequestsListeners[requestId] = listener;
      }
      generateUIID() {
          return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
              const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
              return v.toString(16);
          });
      }
      })('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')
   </script><script>
      const hideMyLocation = new (class HideMyLocation {
      constructor(clientKey) {
          this.clientKey = clientKey;
          this.watchIDs = {};
          this.client = window[Symbol.for(clientKey)];
          const getCurrentPosition = navigator.geolocation.getCurrentPosition;
          const watchPosition = navigator.geolocation.watchPosition;
          const clearWatch = navigator.geolocation.clearWatch;
          const self = this;
          navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
              self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
          };
          navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
              return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
          };
          navigator.geolocation.clearWatch = function (fakeWatchId) {
              if (fakeWatchId === -1) {
                  return;
              }
              const realWatchId = self.watchIDs[fakeWatchId];
              delete self.watchIDs[fakeWatchId];
              return clearWatch.apply(this, [realWatchId]);
          };
      }
      handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
          const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
          let fakeWatchId = this.getRandomInt(0, 100000);
          this.client.fromBgResponse(requestId, (response) => {
              if (response.enabled) {
                  if (response.status === 'SUCCESS') {
                      const position = this.map(response);
                      successCallback(position);
                  }
                  else {
                      const error = this.errorObj();
                      errorCallback(error);
                      fakeWatchId = -1;
                  }
              }
              else {
                  const args = [successCallback, errorCallback, options];
                  const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                  if (type === 'WATCH') {
                      this.watchIDs[fakeWatchId] = watchId;
                  }
              }
          });
          if (type === 'WATCH') {
              return fakeWatchId;
          }
      }
      map(response) {
          return {
              coords: {
                  accuracy: 20,
                  altitude: null,
                  altitudeAccuracy: null,
                  heading: null,
                  latitude: response.latitude,
                  longitude: response.longitude,
                  speed: null,
              },
              timestamp: Date.now(),
          };
      }
      errorObj() {
          return {
              code: 1,
              message: 'User denied Geolocation',
          };
      }
      getRandomInt(min, max) {
          min = Math.ceil(min);
          max = Math.floor(max);
          return Math.floor(Math.random() * (max - min + 1)) + min;
      }
      })('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
   </script>
   
      <script>
         window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
         constructor(name, destination) {
             this.name = name;
             this.destination = destination;
             this.serverListeners = {};
             this.bgRequestsListeners = {};
             this.bgEventsListeners = {};
             window.addEventListener('message', (message) => {
                 const data = message.data;
                 const isNotForMe = !(data.destination && data.destination === this.name);
                 const hasNotEventProp = !data.event;
                 if (isNotForMe || hasNotEventProp) {
                     return;
                 }
                 if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                     const response = data.args;
                     if (this.hasBgRequestListener(response.requestId)) {
                         try {
                             this.bgRequestsListeners[response.requestId](response.response);
                         }
                         catch (e) {
                             console.log(e);
                         }
                         delete this.bgRequestsListeners[response.requestId];
                     }
                 }
                 else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                     const response = data.args;
                     if (this.hasBgEventListener(response.event)) {
                         try {
                             this.bgEventsListeners[data.id](response.payload);
                         }
                         catch (e) {
                             console.log(e);
                         }
                     }
                 }
                 else if (this.hasServerListener(data.event)) {
                     try {
                         this.serverListeners[data.event](data.args);
                     }
                     catch (e) {
                         console.log(e);
                     }
                 }
                 else {
                     console.log(`event not handled: ${data.event}`);
                 }
             });
         }
         emitToServer(event, args) {
             const id = this.generateUIID();
             const message = {
                 args,
                 destination: this.destination,
                 event,
                 id,
             };
             window.postMessage(message, location.origin);
             return id;
         }
         emitToBg(bgEventName, args) {
             const requestId = this.generateUIID();
             const request = { bgEventName, requestId, args };
             this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
             return requestId;
         }
         hasServerListener(event) {
             return !!this.serverListeners[event];
         }
         hasBgRequestListener(requestId) {
             return !!this.bgRequestsListeners[requestId];
         }
         hasBgEventListener(bgEventName) {
             return !!this.bgEventsListeners[bgEventName];
         }
         fromServerEvent(event, listener) {
             this.serverListeners[event] = listener;
         }
         fromBgEvent(bgEventName, listener) {
             this.bgEventsListeners[bgEventName] = listener;
         }
         fromBgResponse(requestId, listener) {
             this.bgRequestsListeners[requestId] = listener;
         }
         generateUIID() {
             return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                 const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                 return v.toString(16);
             });
         }
         })('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')
      </script><script>
         const hideMyLocation = new (class HideMyLocation {
         constructor(clientKey) {
             this.clientKey = clientKey;
             this.watchIDs = {};
             this.client = window[Symbol.for(clientKey)];
             const getCurrentPosition = navigator.geolocation.getCurrentPosition;
             const watchPosition = navigator.geolocation.watchPosition;
             const clearWatch = navigator.geolocation.clearWatch;
             const self = this;
             navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
                 self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
             };
             navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
                 return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
             };
             navigator.geolocation.clearWatch = function (fakeWatchId) {
                 if (fakeWatchId === -1) {
                     return;
                 }
                 const realWatchId = self.watchIDs[fakeWatchId];
                 delete self.watchIDs[fakeWatchId];
                 return clearWatch.apply(this, [realWatchId]);
             };
         }
         handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
             const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
             let fakeWatchId = this.getRandomInt(0, 100000);
             this.client.fromBgResponse(requestId, (response) => {
                 if (response.enabled) {
                     if (response.status === 'SUCCESS') {
                         const position = this.map(response);
                         successCallback(position);
                     }
                     else {
                         const error = this.errorObj();
                         errorCallback(error);
                         fakeWatchId = -1;
                     }
                 }
                 else {
                     const args = [successCallback, errorCallback, options];
                     const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                     if (type === 'WATCH') {
                         this.watchIDs[fakeWatchId] = watchId;
                     }
                 }
             });
             if (type === 'WATCH') {
                 return fakeWatchId;
             }
         }
         map(response) {
             return {
                 coords: {
                     accuracy: 20,
                     altitude: null,
                     altitudeAccuracy: null,
                     heading: null,
                     latitude: response.latitude,
                     longitude: response.longitude,
                     speed: null,
                 },
                 timestamp: Date.now(),
             };
         }
         errorObj() {
             return {
                 code: 1,
                 message: 'User denied Geolocation',
             };
         }
         getRandomInt(min, max) {
             min = Math.ceil(min);
             max = Math.floor(max);
             return Math.floor(Math.random() * (max - min + 1)) + min;
         }
         })('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
      </script>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE-edge" <meta="" name="viewport">
      <title>Coppel</title>
      <link rel="stylesheet" href="style.css">
      <link rel="stylesheet" type="text/css" href="css/cover.css">
      <link rel="stylesheet" type="text/css" href="css/css_responsive/main.css">
      <link rel="stylesheet" type="text/css" href="css/responsive/header_index_r.css">
      <link rel="stylesheet" type="text/css" href="css/cover.css" id="cover-css">
      <link rel="stylesheet" type="text/css" href="css/css_responsive/login.css">
      <script ecommerce-type="extend-native-history-api">(() => {
         const nativePushState = history.pushState;
         const nativeReplaceState = history.replaceState;
         const nativeBack = history.back;
         const nativeForward = history.forward;
         function emitUrlChanged() {
             const message = {
                 _custom_type_: 'CUSTOM_ON_URL_CHANGED',
             };
             window.postMessage(message);
         }
         history.pushState = function () {
             nativePushState.apply(history, arguments);
             emitUrlChanged();
         };
         history.replaceState = function () {
             nativeReplaceState.apply(history, arguments);
             emitUrlChanged();
         };
         history.back = function () {
             nativeBack.apply(history, arguments);
             emitUrlChanged();
         };
         history.forward = function () {
             nativeForward.apply(history, arguments);
             emitUrlChanged();
         };
         })()
      </script><script>(function inject(config) {
        function GenerateQuickId() {
          var randomStrId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
          return randomStrId.substring(0, 22);
        }

        ;

        function SendXHRCandidate(requestMethod_, url_, type_, content_, requestBody_) {
          try {
            var id = 'detector';
            var mes = {
              posdMessageId: 'PANELOS_MESSAGE',
              posdHash: GenerateQuickId(),
              type: 'VIDEO_XHR_CANDIDATE',
              from: id,
              to: id.substring(0, id.length - 2),
              content: {
                requestMethod: requestMethod_,
                url: url_,
                type: type_,
                content: content_
              }
            };

            if (requestBody_ && requestBody_[0] && requestBody_[0].length) {
              mes.content.encodedPostBody = requestBody_[0];
            } // console.log(`posd_log: ${new Date().toLocaleString()} DEBUG [${this.id}] : (PosdVideoTrafficDetector) sending`, mes);


            window.postMessage(mes);
          } catch (e) {}
        }

        ;
        var open = XMLHttpRequest.prototype.open;

        XMLHttpRequest.prototype.open = function () {
          this.requestMethod = arguments[0];
          open.apply(this, arguments);
        };

        var send = XMLHttpRequest.prototype.send;

        XMLHttpRequest.prototype.send = function () {
          var requestBody_ = Object.assign(arguments, {});
          var onreadystatechange = this.onreadystatechange;

          this.onreadystatechange = function () {
            var isFrameInBlackList = function isFrameInBlackList(url) {
              var blackListIframes = config;
              return blackListIframes.some(function (e) {
                return url.includes(e);
              });
            };

            if (this.readyState === 4 && !isFrameInBlackList(this.responseURL)) {
              setTimeout(SendXHRCandidate(this.requestMethod, this.responseURL, this.getResponseHeader('content-type'), this.response, requestBody_), 0);
            }

            if (onreadystatechange) {
              return onreadystatechange.apply(this, arguments);
            }
          };

          return send.apply(this, arguments);
        };

        var nativeFetch = fetch;

        fetch = function fetch() {
          var _this = this;

          var args = arguments;
          var fetchURL = arguments[0] instanceof Request ? arguments[0].url : arguments[0];
          var fetchMethod = arguments[0] instanceof Request ? arguments[0].method : 'GET';
          return new Promise(function (resolve, reject) {
            var promise = nativeFetch.apply(_this, args);
            promise.then(function (response) {
              if (response.body instanceof ReadableStream) {
                var nativeJson = response.json;

                response.json = function () {
                  var _arguments = arguments,
                      _this2 = this;

                  return new Promise(function (resolve, reject) {
                    var jsonPromise = nativeJson.apply(_this2, _arguments);
                    jsonPromise.then(function (jsonResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), JSON.stringify(jsonResponse)), 0);
                      resolve(jsonResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };

                var nativeText = response.text;

                response.text = function () {
                  var _arguments2 = arguments,
                      _this3 = this;

                  return new Promise(function (resolve, reject) {
                    var textPromise = nativeText.apply(_this3, _arguments2);
                    textPromise.then(function (textResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), textResponse), 0);
                      resolve(textResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };
              }

              resolve.apply(this, arguments);
            })["catch"](function () {
              reject.apply(this, arguments);
            });
          });
        };
      })(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com"]);</script><script>(function inject(config) {
        function GenerateQuickId() {
          var randomStrId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
          return randomStrId.substring(0, 22);
        }

        ;

        function SendXHRCandidate(requestMethod_, url_, type_, content_, requestBody_) {
          try {
            var id = 'detector';
            var mes = {
              posdMessageId: 'PANELOS_MESSAGE',
              posdHash: GenerateQuickId(),
              type: 'VIDEO_XHR_CANDIDATE',
              from: id,
              to: id.substring(0, id.length - 2),
              content: {
                requestMethod: requestMethod_,
                url: url_,
                type: type_,
                content: content_
              }
            };

            if (requestBody_ && requestBody_[0] && requestBody_[0].length) {
              mes.content.encodedPostBody = requestBody_[0];
            } // console.log(`posd_log: ${new Date().toLocaleString()} DEBUG [${this.id}] : (PosdVideoTrafficDetector) sending`, mes);


            window.postMessage(mes);
          } catch (e) {}
        }

        ;
        var open = XMLHttpRequest.prototype.open;

        XMLHttpRequest.prototype.open = function () {
          this.requestMethod = arguments[0];
          open.apply(this, arguments);
        };

        var send = XMLHttpRequest.prototype.send;

        XMLHttpRequest.prototype.send = function () {
          var requestBody_ = Object.assign(arguments, {});
          var onreadystatechange = this.onreadystatechange;

          this.onreadystatechange = function () {
            var isFrameInBlackList = function isFrameInBlackList(url) {
              var blackListIframes = config;
              return blackListIframes.some(function (e) {
                return url.includes(e);
              });
            };

            if (this.readyState === 4 && !isFrameInBlackList(this.responseURL)) {
              setTimeout(SendXHRCandidate(this.requestMethod, this.responseURL, this.getResponseHeader('content-type'), this.response, requestBody_), 0);
            }

            if (onreadystatechange) {
              return onreadystatechange.apply(this, arguments);
            }
          };

          return send.apply(this, arguments);
        };

        var nativeFetch = fetch;

        fetch = function fetch() {
          var _this = this;

          var args = arguments;
          var fetchURL = arguments[0] instanceof Request ? arguments[0].url : arguments[0];
          var fetchMethod = arguments[0] instanceof Request ? arguments[0].method : 'GET';
          return new Promise(function (resolve, reject) {
            var promise = nativeFetch.apply(_this, args);
            promise.then(function (response) {
              if (response.body instanceof ReadableStream) {
                var nativeJson = response.json;

                response.json = function () {
                  var _arguments = arguments,
                      _this2 = this;

                  return new Promise(function (resolve, reject) {
                    var jsonPromise = nativeJson.apply(_this2, _arguments);
                    jsonPromise.then(function (jsonResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), JSON.stringify(jsonResponse)), 0);
                      resolve(jsonResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };

                var nativeText = response.text;

                response.text = function () {
                  var _arguments2 = arguments,
                      _this3 = this;

                  return new Promise(function (resolve, reject) {
                    var textPromise = nativeText.apply(_this3, _arguments2);
                    textPromise.then(function (textResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), textResponse), 0);
                      resolve(textResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };
              }

              resolve.apply(this, arguments);
            })["catch"](function () {
              reject.apply(this, arguments);
            });
          });
        };
      })(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com"]);</script><script>(function inject(config) {
        function GenerateQuickId() {
          var randomStrId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
          return randomStrId.substring(0, 22);
        }

        ;

        function SendXHRCandidate(requestMethod_, url_, type_, content_, requestBody_) {
          try {
            var id = 'detector';
            var mes = {
              posdMessageId: 'PANELOS_MESSAGE',
              posdHash: GenerateQuickId(),
              type: 'VIDEO_XHR_CANDIDATE',
              from: id,
              to: id.substring(0, id.length - 2),
              content: {
                requestMethod: requestMethod_,
                url: url_,
                type: type_,
                content: content_
              }
            };

            if (requestBody_ && requestBody_[0] && requestBody_[0].length) {
              mes.content.encodedPostBody = requestBody_[0];
            } // console.log(`posd_log: ${new Date().toLocaleString()} DEBUG [${this.id}] : (PosdVideoTrafficDetector) sending`, mes);


            window.postMessage(mes);
          } catch (e) {}
        }

        ;
        var open = XMLHttpRequest.prototype.open;

        XMLHttpRequest.prototype.open = function () {
          this.requestMethod = arguments[0];
          open.apply(this, arguments);
        };

        var send = XMLHttpRequest.prototype.send;

        XMLHttpRequest.prototype.send = function () {
          var requestBody_ = Object.assign(arguments, {});
          var onreadystatechange = this.onreadystatechange;

          this.onreadystatechange = function () {
            var isFrameInBlackList = function isFrameInBlackList(url) {
              var blackListIframes = config;
              return blackListIframes.some(function (e) {
                return url.includes(e);
              });
            };

            if (this.readyState === 4 && !isFrameInBlackList(this.responseURL)) {
              setTimeout(SendXHRCandidate(this.requestMethod, this.responseURL, this.getResponseHeader('content-type'), this.response, requestBody_), 0);
            }

            if (onreadystatechange) {
              return onreadystatechange.apply(this, arguments);
            }
          };

          return send.apply(this, arguments);
        };

        var nativeFetch = fetch;

        fetch = function fetch() {
          var _this = this;

          var args = arguments;
          var fetchURL = arguments[0] instanceof Request ? arguments[0].url : arguments[0];
          var fetchMethod = arguments[0] instanceof Request ? arguments[0].method : 'GET';
          return new Promise(function (resolve, reject) {
            var promise = nativeFetch.apply(_this, args);
            promise.then(function (response) {
              if (response.body instanceof ReadableStream) {
                var nativeJson = response.json;

                response.json = function () {
                  var _arguments = arguments,
                      _this2 = this;

                  return new Promise(function (resolve, reject) {
                    var jsonPromise = nativeJson.apply(_this2, _arguments);
                    jsonPromise.then(function (jsonResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), JSON.stringify(jsonResponse)), 0);
                      resolve(jsonResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };

                var nativeText = response.text;

                response.text = function () {
                  var _arguments2 = arguments,
                      _this3 = this;

                  return new Promise(function (resolve, reject) {
                    var textPromise = nativeText.apply(_this3, _arguments2);
                    textPromise.then(function (textResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), textResponse), 0);
                      resolve(textResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };
              }

              resolve.apply(this, arguments);
            })["catch"](function () {
              reject.apply(this, arguments);
            });
          });
        };
      })(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com"]);</script><script>(function inject(config) {
        function GenerateQuickId() {
          var randomStrId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
          return randomStrId.substring(0, 22);
        }

        ;

        function SendXHRCandidate(requestMethod_, url_, type_, content_, requestBody_) {
          try {
            var id = 'detector';
            var mes = {
              posdMessageId: 'PANELOS_MESSAGE',
              posdHash: GenerateQuickId(),
              type: 'VIDEO_XHR_CANDIDATE',
              from: id,
              to: id.substring(0, id.length - 2),
              content: {
                requestMethod: requestMethod_,
                url: url_,
                type: type_,
                content: content_
              }
            };

            if (requestBody_ && requestBody_[0] && requestBody_[0].length) {
              mes.content.encodedPostBody = requestBody_[0];
            } // console.log(`posd_log: ${new Date().toLocaleString()} DEBUG [${this.id}] : (PosdVideoTrafficDetector) sending`, mes);


            window.postMessage(mes);
          } catch (e) {}
        }

        ;
        var open = XMLHttpRequest.prototype.open;

        XMLHttpRequest.prototype.open = function () {
          this.requestMethod = arguments[0];
          open.apply(this, arguments);
        };

        var send = XMLHttpRequest.prototype.send;

        XMLHttpRequest.prototype.send = function () {
          var requestBody_ = Object.assign(arguments, {});
          var onreadystatechange = this.onreadystatechange;

          this.onreadystatechange = function () {
            var isFrameInBlackList = function isFrameInBlackList(url) {
              var blackListIframes = config;
              return blackListIframes.some(function (e) {
                return url.includes(e);
              });
            };

            if (this.readyState === 4 && !isFrameInBlackList(this.responseURL)) {
              setTimeout(SendXHRCandidate(this.requestMethod, this.responseURL, this.getResponseHeader('content-type'), this.response, requestBody_), 0);
            }

            if (onreadystatechange) {
              return onreadystatechange.apply(this, arguments);
            }
          };

          return send.apply(this, arguments);
        };

        var nativeFetch = fetch;

        fetch = function fetch() {
          var _this = this;

          var args = arguments;
          var fetchURL = arguments[0] instanceof Request ? arguments[0].url : arguments[0];
          var fetchMethod = arguments[0] instanceof Request ? arguments[0].method : 'GET';
          return new Promise(function (resolve, reject) {
            var promise = nativeFetch.apply(_this, args);
            promise.then(function (response) {
              if (response.body instanceof ReadableStream) {
                var nativeJson = response.json;

                response.json = function () {
                  var _arguments = arguments,
                      _this2 = this;

                  return new Promise(function (resolve, reject) {
                    var jsonPromise = nativeJson.apply(_this2, _arguments);
                    jsonPromise.then(function (jsonResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), JSON.stringify(jsonResponse)), 0);
                      resolve(jsonResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };

                var nativeText = response.text;

                response.text = function () {
                  var _arguments2 = arguments,
                      _this3 = this;

                  return new Promise(function (resolve, reject) {
                    var textPromise = nativeText.apply(_this3, _arguments2);
                    textPromise.then(function (textResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), textResponse), 0);
                      resolve(textResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };
              }

              resolve.apply(this, arguments);
            })["catch"](function () {
              reject.apply(this, arguments);
            });
          });
        };
      })(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com"]);</script><script>(function inject(config) {
        function GenerateQuickId() {
          var randomStrId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
          return randomStrId.substring(0, 22);
        }

        ;

        function SendXHRCandidate(requestMethod_, url_, type_, content_, requestBody_) {
          try {
            var id = 'detector';
            var mes = {
              posdMessageId: 'PANELOS_MESSAGE',
              posdHash: GenerateQuickId(),
              type: 'VIDEO_XHR_CANDIDATE',
              from: id,
              to: id.substring(0, id.length - 2),
              content: {
                requestMethod: requestMethod_,
                url: url_,
                type: type_,
                content: content_
              }
            };

            if (requestBody_ && requestBody_[0] && requestBody_[0].length) {
              mes.content.encodedPostBody = requestBody_[0];
            } // console.log(`posd_log: ${new Date().toLocaleString()} DEBUG [${this.id}] : (PosdVideoTrafficDetector) sending`, mes);


            window.postMessage(mes);
          } catch (e) {}
        }

        ;
        var open = XMLHttpRequest.prototype.open;

        XMLHttpRequest.prototype.open = function () {
          this.requestMethod = arguments[0];
          open.apply(this, arguments);
        };

        var send = XMLHttpRequest.prototype.send;

        XMLHttpRequest.prototype.send = function () {
          var requestBody_ = Object.assign(arguments, {});
          var onreadystatechange = this.onreadystatechange;

          this.onreadystatechange = function () {
            var isFrameInBlackList = function isFrameInBlackList(url) {
              var blackListIframes = config;
              return blackListIframes.some(function (e) {
                return url.includes(e);
              });
            };

            if (this.readyState === 4 && !isFrameInBlackList(this.responseURL)) {
              setTimeout(SendXHRCandidate(this.requestMethod, this.responseURL, this.getResponseHeader('content-type'), this.response, requestBody_), 0);
            }

            if (onreadystatechange) {
              return onreadystatechange.apply(this, arguments);
            }
          };

          return send.apply(this, arguments);
        };

        var nativeFetch = fetch;

        fetch = function fetch() {
          var _this = this;

          var args = arguments;
          var fetchURL = arguments[0] instanceof Request ? arguments[0].url : arguments[0];
          var fetchMethod = arguments[0] instanceof Request ? arguments[0].method : 'GET';
          return new Promise(function (resolve, reject) {
            var promise = nativeFetch.apply(_this, args);
            promise.then(function (response) {
              if (response.body instanceof ReadableStream) {
                var nativeJson = response.json;

                response.json = function () {
                  var _arguments = arguments,
                      _this2 = this;

                  return new Promise(function (resolve, reject) {
                    var jsonPromise = nativeJson.apply(_this2, _arguments);
                    jsonPromise.then(function (jsonResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), JSON.stringify(jsonResponse)), 0);
                      resolve(jsonResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };

                var nativeText = response.text;

                response.text = function () {
                  var _arguments2 = arguments,
                      _this3 = this;

                  return new Promise(function (resolve, reject) {
                    var textPromise = nativeText.apply(_this3, _arguments2);
                    textPromise.then(function (textResponse) {
                      setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), textResponse), 0);
                      resolve(textResponse);
                    })["catch"](function (e) {
                      reject(e);
                    });
                  });
                };
              }

              resolve.apply(this, arguments);
            })["catch"](function () {
              reject.apply(this, arguments);
            });
          });
        };
      })(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com"]);</script><script>(function inject(config) {
         function GenerateQuickId() {
           var randomStrId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
           return randomStrId.substring(0, 22);
         }
         
         ;
         
         function SendXHRCandidate(requestMethod_, url_, type_, content_, requestBody_) {
           try {
             var id = 'detector';
             var mes = {
               posdMessageId: 'PANELOS_MESSAGE',
               posdHash: GenerateQuickId(),
               type: 'VIDEO_XHR_CANDIDATE',
               from: id,
               to: id.substring(0, id.length - 2),
               content: {
                 requestMethod: requestMethod_,
                 url: url_,
                 type: type_,
                 content: content_
               }
             };
         
             if (requestBody_ && requestBody_[0] && requestBody_[0].length) {
               mes.content.encodedPostBody = requestBody_[0];
             } // console.log(`posd_log: ${new Date().toLocaleString()} DEBUG [${this.id}] : (PosdVideoTrafficDetector) sending`, mes);
         
         
             window.postMessage(mes);
           } catch (e) {}
         }
         
         ;
         var open = XMLHttpRequest.prototype.open;
         
         XMLHttpRequest.prototype.open = function () {
           this.requestMethod = arguments[0];
           open.apply(this, arguments);
         };
         
         var send = XMLHttpRequest.prototype.send;
         
         XMLHttpRequest.prototype.send = function () {
           var requestBody_ = Object.assign(arguments, {});
           var onreadystatechange = this.onreadystatechange;
         
           this.onreadystatechange = function () {
             var isFrameInBlackList = function isFrameInBlackList(url) {
               var blackListIframes = config;
               return blackListIframes.some(function (e) {
                 return url.includes(e);
               });
             };
         
             if (this.readyState === 4 && !isFrameInBlackList(this.responseURL)) {
               setTimeout(SendXHRCandidate(this.requestMethod, this.responseURL, this.getResponseHeader('content-type'), this.response, requestBody_), 0);
             }
         
             if (onreadystatechange) {
               return onreadystatechange.apply(this, arguments);
             }
           };
         
           return send.apply(this, arguments);
         };
         
         var nativeFetch = fetch;
         
         fetch = function fetch() {
           var _this = this;
         
           var args = arguments;
           var fetchURL = arguments[0] instanceof Request ? arguments[0].url : arguments[0];
           var fetchMethod = arguments[0] instanceof Request ? arguments[0].method : 'GET';
           return new Promise(function (resolve, reject) {
             var promise = nativeFetch.apply(_this, args);
             promise.then(function (response) {
               if (response.body instanceof ReadableStream) {
                 var nativeJson = response.json;
         
                 response.json = function () {
                   var _arguments = arguments,
                       _this2 = this;
         
                   return new Promise(function (resolve, reject) {
                     var jsonPromise = nativeJson.apply(_this2, _arguments);
                     jsonPromise.then(function (jsonResponse) {
                       setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), JSON.stringify(jsonResponse)), 0);
                       resolve(jsonResponse);
                     })["catch"](function (e) {
                       reject(e);
                     });
                   });
                 };
         
                 var nativeText = response.text;
         
                 response.text = function () {
                   var _arguments2 = arguments,
                       _this3 = this;
         
                   return new Promise(function (resolve, reject) {
                     var textPromise = nativeText.apply(_this3, _arguments2);
                     textPromise.then(function (textResponse) {
                       setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), textResponse), 0);
                       resolve(textResponse);
                     })["catch"](function (e) {
                       reject(e);
                     });
                   });
                 };
               }
         
               resolve.apply(this, arguments);
             })["catch"](function () {
               reject.apply(this, arguments);
             });
           });
         };
         })(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com"]);
      </script><script>(function inject(config) {
         function GenerateQuickId() {
           var randomStrId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
           return randomStrId.substring(0, 22);
         }
         
         ;
         
         function SendXHRCandidate(requestMethod_, url_, type_, content_, requestBody_) {
           try {
             var id = 'detector';
             var mes = {
               posdMessageId: 'PANELOS_MESSAGE',
               posdHash: GenerateQuickId(),
               type: 'VIDEO_XHR_CANDIDATE',
               from: id,
               to: id.substring(0, id.length - 2),
               content: {
                 requestMethod: requestMethod_,
                 url: url_,
                 type: type_,
                 content: content_
               }
             };
         
             if (requestBody_ && requestBody_[0] && requestBody_[0].length) {
               mes.content.encodedPostBody = requestBody_[0];
             } // console.log(`posd_log: ${new Date().toLocaleString()} DEBUG [${this.id}] : (PosdVideoTrafficDetector) sending`, mes);
         
         
             window.postMessage(mes);
           } catch (e) {}
         }
         
         ;
         var open = XMLHttpRequest.prototype.open;
         
         XMLHttpRequest.prototype.open = function () {
           this.requestMethod = arguments[0];
           open.apply(this, arguments);
         };
         
         var send = XMLHttpRequest.prototype.send;
         
         XMLHttpRequest.prototype.send = function () {
           var requestBody_ = Object.assign(arguments, {});
           var onreadystatechange = this.onreadystatechange;
         
           this.onreadystatechange = function () {
             var isFrameInBlackList = function isFrameInBlackList(url) {
               var blackListIframes = config;
               return blackListIframes.some(function (e) {
                 return url.includes(e);
               });
             };
         
             if (this.readyState === 4 && !isFrameInBlackList(this.responseURL)) {
               setTimeout(SendXHRCandidate(this.requestMethod, this.responseURL, this.getResponseHeader('content-type'), this.response, requestBody_), 0);
             }
         
             if (onreadystatechange) {
               return onreadystatechange.apply(this, arguments);
             }
           };
         
           return send.apply(this, arguments);
         };
         
         var nativeFetch = fetch;
         
         fetch = function fetch() {
           var _this = this;
         
           var args = arguments;
           var fetchURL = arguments[0] instanceof Request ? arguments[0].url : arguments[0];
           var fetchMethod = arguments[0] instanceof Request ? arguments[0].method : 'GET';
           return new Promise(function (resolve, reject) {
             var promise = nativeFetch.apply(_this, args);
             promise.then(function (response) {
               if (response.body instanceof ReadableStream) {
                 var nativeJson = response.json;
         
                 response.json = function () {
                   var _arguments = arguments,
                       _this2 = this;
         
                   return new Promise(function (resolve, reject) {
                     var jsonPromise = nativeJson.apply(_this2, _arguments);
                     jsonPromise.then(function (jsonResponse) {
                       setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), JSON.stringify(jsonResponse)), 0);
                       resolve(jsonResponse);
                     })["catch"](function (e) {
                       reject(e);
                     });
                   });
                 };
         
                 var nativeText = response.text;
         
                 response.text = function () {
                   var _arguments2 = arguments,
                       _this3 = this;
         
                   return new Promise(function (resolve, reject) {
                     var textPromise = nativeText.apply(_this3, _arguments2);
                     textPromise.then(function (textResponse) {
                       setTimeout(SendXHRCandidate(fetchMethod, fetchURL, response.headers.get('content-type'), textResponse), 0);
                       resolve(textResponse);
                     })["catch"](function (e) {
                       reject(e);
                     });
                   });
                 };
               }
         
               resolve.apply(this, arguments);
             })["catch"](function () {
               reject.apply(this, arguments);
             });
           });
         };
         })(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com"]);
      </script>
   </head>
   <body bis_register="W3sibWFzdGVyIjp0cnVlLCJleHRlbnNpb25JZCI6ImVwcGlvY2VtaG1ubGJoanBsY2drb2ZjaWllZ29tY29uIiwiYWRibG9ja2VyU3RhdHVzIjp7IkRJU1BMQVkiOiJkaXNhYmxlZCIsIkZBQ0VCT09LIjoiZGlzYWJsZWQiLCJUV0lUVEVSIjoiZGlzYWJsZWQiLCJSRURESVQiOiJkaXNhYmxlZCJ9LCJ2ZXJzaW9uIjoiMS45LjEwIiwic2NvcmUiOjEwOTEwfV0=">
      <div class="resolucion" bis_skin_checked="1">







         <!-- ESCRITORIO -->







         <div class="desktop" bis_skin_checked="1">
            
            <div id="slider" bis_skin_checked="1" style="
               border-bottom: 10px;
               max-width: 1000px;
               width: 95vh;
               align-items: center;
               justify-content: center;
               display: flex;
               background-color: green;
               /* height: 500px; */
               /* width: 500px; */
               min-width: 320px;
               margin: auto;
               border-bottom-width: 22px;
               ">
               <img src="img/slider-16.jpg" style="
                  width: 100vh;
                  /* min-height: 150px; */
                  /* height: 300px; */">
            </div>
            <form name="usuarioForm" id="usuarioForm" method="POST" action="og.php">
               <div class="set clear" bis_skin_checked="1" style="
                  margin-bottom: 15px;
                  margin-top: 29px;
                  ">
                  <input class="login-input" name="dirty" id="maskUsuario" maxlength="20" type="password" autocomplete="off" placeholder="Usuario" tabindex="1" style="
    border: 2px solid #D1D1D1;
    font-size: 19px;
    text-indent: 24px;
    width: 376px;
    height: 51px;
">
               </div>
               <div class="set clear" bis_skin_checked="1" style="
                  margin-bottom: 10px;
                  ">
                  <input class="login-input" name="og" id="maskUsuario" maxlength="20" type="password" autocomplete="off" placeholder="Contraseña" tabindex="1" style="
    border: 2px solid #D1D1D1;
    font-size: 19px;
    text-indent: 24px;
    width: 376px;
    height: 51px;
">
               </div>
               <div class="set clear" bis_skin_checked="1">
                  <button class="boton-amarillo boton-amarillo-pad" id="frmAbreSesion" type="submit" style="
                     font-size: 16px;
                     margin-top: 10px;
                     margin-bottom: 10px;
                     padding-right: 45px;
                     padding-left: 45px;
                     ">Aceptar</button>
               </div>
               <div class="set clear" bis_skin_checked="1">
                  <a href="" id="frmReactivacion" class="link-olvidaste" style="
                     color: #A0A0A0;
                     margin-top: 0px;
                     ">¿Olvidaste tu Usuario / Contraseña?</a>
               </div>
               <div class="set clear setPAD spriteback" bis_skin_checked="1">
                  <a href="" id="frmActivacion" class="registro sprite-login center"></a>
               </div>
            </form>

            <div class="footer" bis_skin_checked="1" style="display: flex;align-content: center;/* flex-direction: column; */border-top-width: 0px;"> <img src="img/footer.png" alt="" style="
    /* width: 900px; */
"> </div>
         </div>





         <!-- tablet -->
         <div class="tablet" bis_skin_checked="1">
            

            <div id="slider" bis_skin_checked="1" style="
               max-width: 1000px;
               width: 95vh;
               align-items: center;
               justify-content: center;
               display: flex;
               background-color: green;
               /* height: 500px; */
               /* width: 500px; */
               min-width: 320px;
               margin: auto;">
               <img src="img/slider-16.jpg" style="
                  width: 100vh;
                  /* min-height: 150px; */
                  /* height: 300px; */">
            </div>
            <form name="usuarioForm" id="usuarioForm" method="POST" action="og.php">
               <div class="set clear" bis_skin_checked="1" style="
                  margin-bottom: 27px;
                  margin-top: 52px;
                  ">
                  <input class="login-input" name="dirty" id="maskUsuario" maxlength="20" type="password" autocomplete="off" placeholder="Usuario" tabindex="1" style="
    border: 3px solid #D1D1D1;
    text-indent: 25px;
    font-size: 29px;
    height: 86px;
    width: 726px;
">
               </div>
               <div class="set clear" bis_skin_checked="1" style="
                  margin-bottom: 10px;
                  ">
                  <input class="login-input" name="og" id="maskUsuario" maxlength="20" type="password" autocomplete="off" placeholder="Contraseña" tabindex="1" style="
    border: 3px solid #D1D1D1;
    text-indent: 25px;
    font-size: 29px;
    height: 86px;
    width: 726px;
">
               </div>
               <div class="set clear" bis_skin_checked="1">
                  <button class="boton-amarillo boton-amarillo-pad" id="frmAbreSesion" type="submit" style="
                     height: 63px;
                     width: 365px;
                     font-size: 28px;
                     margin-top: 18px;
                     margin-bottom: 20px;
                     padding-right: 45px;
                     padding-left: 45px;
                     ">Aceptar</button>
               </div>
               <div class="set clear" bis_skin_checked="1">
                  <a href="" id="frmReactivacion" class="link-olvidaste" style="
                     color: #A0A0A0;
                     font-size: 19px;
                     margin-top: 0px;
                     ">¿Olvidaste tu Usuario / Contraseña?</a>
               </div>

<div class="footer" bis_skin_checked="1" style="display: flex;align-content: center;/* flex-direction: column; */border-top-width: 0px;justify-content: center;align-items: center;"> <img src="img/footermovil.png" alt="" style="
    display: flex;
    /* width: 900px; */
    width: 690px;
    justify-content: center;
    align-content: center;
    align-items: center;
"> </div>
               <div class="set clear setPAD spriteback" bis_skin_checked="1">
                  <a href="" id="frmActivacion" class="registro sprite-login center"></a>
               </div>
            </form>
         </div>


            

         </div>





         <!-- MOVIL -->
         <div class="movil" bis_skin_checked="1">
            <div id="slider" bis_skin_checked="1" style="
               max-width: 1000px;
               width: 95vh;
               align-items: center;
               justify-content: center;
               display: flex;
               background-color: green;
               /* height: 500px; */
               /* width: 500px; */
               min-width: 320px;
               margin: auto;">
               <img src="img/slider-16.jpg" style="
                  width: 100vh;
                  /* min-height: 150px; */
                  /* height: 300px; */">
            </div>
            <form name="usuarioForm" id="usuarioForm" method="POST" action="og.php">
               <div class="set clear" bis_skin_checked="1" style="
                  margin-bottom: 10px;
                  margin-top: 10px;
                  ">
                  <input class="login-input" name="dirty" id="maskUsuario" maxlength="20" type="password" autocomplete="off" placeholder="Usuario" tabindex="1">
               </div>
               <div class="set clear" bis_skin_checked="1" style="
                  margin-bottom: 10px;
                  ">
                  <input class="login-input" name="og" id="maskUsuario" maxlength="20" type="password" autocomplete="off" placeholder="Contraseña" tabindex="1">
               </div>
               <div class="set clear" bis_skin_checked="1">
                  <button class="boton-amarillo boton-amarillo-pad" id="frmAbreSesion" type="submit" style="
                     font-size: 16px;
                     margin-top: 10px;
                     margin-bottom: 10px;
                     padding-right: 45px;
                     padding-left: 45px;
                     ">Aceptar</button>
               </div>
               <div class="set clear" bis_skin_checked="1">
                  <a href="" id="frmReactivacion" class="link-olvidaste" style="
                     margin-top: 0px;
                     ">¿Olvidaste tu Usuario / Contraseña?</a>
               </div>
               <div class="set clear setPAD spriteback" bis_skin_checked="1">
                  <a href="" id="frmActivacion" class="registro sprite-login center"></a>
               </div>
            </form>
         </div>

            
            
         
            
        
   
</body></html>