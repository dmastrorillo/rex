import { router } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { Input } from '../ui/input';

function useSearch(initialSearchQuery: string) {
    const [inputValue, setInputValue] = useState(initialSearchQuery);
    const [searchValue, setSearchValue] = useState(initialSearchQuery);

    const isInitialMount = useRef(true);

    const prevSearchValueRef = useRef<string>(initialSearchQuery);

    useEffect(() => {
        // Skip the URL update on initial mount
        if (isInitialMount.current) {
            isInitialMount.current = false;
            return;
        }

        const debounceTimeout = setTimeout(() => {
            setSearchValue(inputValue);
        }, 500);

        return () => clearTimeout(debounceTimeout);
    }, [inputValue]);

    // URL update handler
    useEffect(() => {
        // Skip the URL update on initial mount
        if (prevSearchValueRef.current === searchValue) {
            return;
        }

        prevSearchValueRef.current = searchValue;

        const searchParams = new URLSearchParams(window.location.search);

        if (searchValue.trim()) {
            searchParams.set('searchQuery', searchValue);
        } else {
            searchParams.delete('searchQuery');
        }

        // Reset pagination on search query change
        searchParams.delete('page');

        const stringifiedParams = searchParams.toString();
        const url = `/contacts${stringifiedParams ? `?${stringifiedParams}` : ''}`;

        router.visit(url, { preserveScroll: true, preserveState: true });
    }, [searchValue]);

    return {
        inputValue,
        setInputValue,
    };
}

export type ContactsSearchProps = {
    initialSearchQuery: string;
};

export function ContactsSearch({ initialSearchQuery }: ContactsSearchProps) {
    const { inputValue, setInputValue } = useSearch(initialSearchQuery);
    return (
        <Input
            placeholder="Search contacts..."
            className="bg-primary placeholder:text-muted-foreground selection:bg-secondary selection:text-secondary-foreground text-secondary"
            value={inputValue}
            onChange={(e) => setInputValue(e.target.value)}
        />
    );
}
