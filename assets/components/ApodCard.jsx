import { useState, useEffect } from 'react';

export default function ApodCard() {
    const [apod, setApod] = useState(null);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch('/fetch/latest')
            .then(res => {
                if (!res.ok) throw new Error('Fetch failed');
                return res.json();
            })
            .then(setApod)
            .catch(err => setError(err.message));
    }, []);

    if (error) return <p>Error: {error}</p>;
    if (!apod) return <p>Loading...</p>;

    return (
        <div className="apod-card">
            <h2>{apod.title}</h2>
            <p>{apod.date}</p>
            {apod.mediaType === 'image' && <img src={apod.url} alt={apod.title} />}
            <p>{apod.explanation}</p>
        </div>
    );
}
