import { PaginatedData } from '@/types';
import { TableBody } from './table-body';
import { TableHead } from './table-head';
import { ColumnInfo, GenericTableData } from './types';
import { TableFooter } from './table-footer';

export type TableProps<T extends GenericTableData[]> = {
    data: T;
    /**
     * Key-value mapping the column data to the display name
     */
    columnInfo: ColumnInfo<T>;

    paginationData?: PaginatedData<T[number]>;
};

export function Table<T extends GenericTableData[]>({ data, columnInfo, paginationData }: TableProps<T>) {
    return (
        <>
            <table className="w-full">
                <TableHead columns={Object.values(columnInfo).filter(Boolean) as string[]} />
                <TableBody data={data} columns={Object.keys(columnInfo)} />
            </table>
            {paginationData && (<TableFooter data={paginationData} />)}
        </>
    );
}
