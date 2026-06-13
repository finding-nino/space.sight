import './styles/app.css';

import ReactDOM from 'react-dom/client';
import ApodCard from './components/ApodCard';

const container = document.getElementById('apod-root');
if (container) {
    ReactDOM.createRoot(container).render(<ApodCard />);
}
