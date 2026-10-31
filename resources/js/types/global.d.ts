import type { AxiosInstance } from 'axios';
import type { AuthData, FlashData, GroupData } from '@/types/generated';

declare global {
  interface Window {
    axios: AxiosInstance;
  }
}

export type AuthUser = AuthData;

export type FlashProps = FlashData;

export type Paginated<T> = {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

declare module '@inertiajs/core' {
  export interface PageProps {
    auth: AuthData;
    flash: FlashData;
    activeGroup: GroupData | null;
    userGroups: GroupData[];
  }
}

export {};
