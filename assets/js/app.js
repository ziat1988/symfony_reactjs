import React from 'react';
import ReactDOM from 'react-dom';

import '../scss/app.scss';
class App extends React.Component {
    render() {
        return (
            <div>
                <p>Hello there. Just a test  </p>
            </div>
        )
    }
}
ReactDOM.render(<App/>, document.getElementById('root'));

console.log('day la trang app');